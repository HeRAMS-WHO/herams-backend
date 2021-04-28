<?php
declare(strict_types=1);

use JCIT\jobqueue\components\ContainerMapLocator;
use JCIT\jobqueue\components\jobQueues\Synchronous;
use JCIT\jobqueue\factories\JobFactory;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use prime\assets\JqueryBundle;
use prime\components\UserNotificationService;
use prime\jobHandlers\accessRequests\CreatedNotificationHandler as AccessRequestCreatedNotificationHandler;
use prime\jobHandlers\accessRequests\ImplicitlyGrantedHandler as AccessRequestImplicitlyGrantedHandler;
use prime\jobHandlers\accessRequests\ResponseNotificationHandler as AccessRequestResponseNotificationHandler;
use prime\jobHandlers\permissions\CheckImplicitAccessRequestGrantedHandler as PermissionCheckImplicitAccessRequestGrantedHandler;
use prime\jobs\accessRequests\CreatedNotificationJob as AccessRequestCreatedNotificationJob;
use prime\jobs\accessRequests\ImplicitlyGrantedJob as AccessrequestImplicitlyGrantedJob;
use prime\jobs\accessRequests\ResponseNotificationJob as AccessRequestResponseNotificationJob;
use prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob as PermissionCheckImplicitAccessRequestGrantedJob;
use prime\models\ar\Permission;
use prime\repositories\PermissionRepository as PermissionARRepository;
use SamIT\abac\interfaces\PermissionRepository;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\repositories\CachedReadRepository;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\abac\resolvers\ChainedResolver;
use SamIT\Yii2\abac\ActiveRecordRepository;
use SamIT\Yii2\abac\ActiveRecordResolver;
use yii\di\Container;
use yii\mail\MailerInterface;
use yii\web\JqueryAsset;

return [
    \kartik\dialog\Dialog::class => \yii\base\Widget::class,
    \prime\interfaces\AccessCheckInterface::class => \prime\helpers\AccessCheck::class,
    \prime\helpers\AccessCheck::class => static function () {
        return new \prime\helpers\AccessCheck(\Yii::$app->user);
    },
    \prime\helpers\LimesurveyDataLoader::class => \prime\helpers\LimesurveyDataLoader::class,
    JqueryAsset::class => JqueryBundle::class,
    PermissionRepository::class => PreloadingSourceRepository::class,
    PreloadingSourceRepository::class =>
        fn (Container $container) => new PreloadingSourceRepository($container->get(CachedReadRepository::class)),
    CachedReadRepository::class => function (Container $container) {
        return new CachedReadRepository($container->get(ActiveRecordRepository::class));
    },
    \SamIT\abac\interfaces\RuleEngine::class => static function () {
        return new \SamIT\abac\engines\SimpleEngine(require __DIR__ . '/rule-config.php');
    },
    Resolver::class => static function (): Resolver {
        return new ChainedResolver(
            new \prime\components\SingleTableInheritanceResolver(),
            new ActiveRecordResolver(),
            new \prime\components\GlobalPermissionResolver()
        );
    },
    ActiveRecordRepository::class => static function () {
        return new ActiveRecordRepository(Permission::class, [
            ActiveRecordRepository::SOURCE_ID => ActiveRecordRepository::SOURCE_ID,
            ActiveRecordRepository::SOURCE_NAME => 'source',
            ActiveRecordRepository::TARGET_ID => ActiveRecordRepository::TARGET_ID,
            ActiveRecordRepository::TARGET_NAME => 'target',
            ActiveRecordRepository::PERMISSION => ActiveRecordRepository::PERMISSION
        ]);
    },
    ActionColumn::class => static function (Container $container, array $params = [], array $config = []): \yii\grid\ActionColumn {
        if (!isset($config['header'])) {
            $config['header'] = \Yii::t('app', 'Actions');
        }
        $result = new ActionColumn($config);
        return $result;
    },
    \kartik\switchinput\SwitchInput::class => static function (Container $container, array $params, array $config) {
        $config = \yii\helpers\ArrayHelper::merge([
            'pluginOptions' => [
                'offText' => \Yii::t('app', 'Off'),
                'onText' => \Yii::t('app', 'On'),
            ]
        ], $config);
        return new \kartik\switchinput\SwitchInput($config);
    },
    GridView::class => static function (Container $container, array $params, array $config): GridView {
        $result = new GridView($config);
        $result->export = false;
        $result->toggleData = false;
        return $result;
    },
    UserNotificationService::class => static function (Container $container, array $params, array $config): UserNotificationService {
        $result = new UserNotificationService(\Yii::$app->abacManager);
        return $result;
    },
    CommandBus::class => function (Container $container) {
        return new CommandBus([
            new CommandHandlerMiddleware(
                $container->get(CommandNameExtractor::class),
                $container->get(HandlerLocator::class),
                $container->get(MethodNameInflector::class)
            )
        ]);
    },
    ContainerMapLocator::class => function (Container $container) {
        return (new ContainerMapLocator($container))
            ->setHandlerForCommand(AccessRequestCreatedNotificationJob::class, AccessRequestCreatedNotificationHandler::class)
            ->setHandlerForCommand(AccessrequestImplicitlyGrantedJob::class, AccessRequestImplicitlyGrantedHandler::class)
            ->setHandlerForCommand(AccessRequestResponseNotificationJob::class, AccessRequestResponseNotificationHandler::class)
            ->setHandlerForCommand(PermissionCheckImplicitAccessRequestGrantedJob::class, PermissionCheckImplicitAccessRequestGrantedHandler::class)
            ;
    },
    CommandNameExtractor::class => ClassNameExtractor::class,
    HandlerLocator::class => ContainerMapLocator::class,
    JobFactoryInterface::class => JobFactory::class,
    JobQueueInterface::class => Synchronous::class,
    MethodNameInflector::class => HandleInflector::class,
    MailerInterface::class => static function (Container $container, array $params, array $config): MailerInterface {
        return \Yii::$app->mailer;
    },
    PermissionCheckImplicitAccessRequestGrantedHandler::class => static function (Container $container, array $params, array $config): PermissionCheckImplicitAccessRequestGrantedHandler {
        return new PermissionCheckImplicitAccessRequestGrantedHandler(
            \Yii::$app->abacManager,
            $container->get(Resolver::class),
            $container->get(JobQueueInterface::class),
            $container->get(PermissionARRepository::class)
        );
    }
];
