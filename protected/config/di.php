<?php

declare(strict_types=1);

use DrewM\MailChimp\MailChimp;
use JCIT\jobqueue\components\ContainerMapLocator;
use JCIT\jobqueue\components\jobQueues\Synchronous;
use JCIT\jobqueue\factories\JobFactory;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use prime\assets\JqueryBundle;
use prime\behaviors\AuditableBehavior;
use prime\components\GlobalPermissionResolver;
use prime\components\NewsletterService;
use prime\components\ReadWriteModelResolver;
use prime\components\SingleTableInheritanceResolver;
use prime\helpers\AccessCheck;
use prime\helpers\EventDispatcherProxy;
use prime\helpers\JobQueueProxy;
use prime\helpers\LimesurveyDataLoader;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\EventDispatcherInterface;
use prime\jobHandlers\accessRequests\CreatedNotificationHandler as AccessRequestCreatedNotificationHandler;
use prime\jobHandlers\accessRequests\ImplicitlyGrantedNotificationHandler as AccessRequestImplicitlyGrantedHandler;
use prime\jobHandlers\accessRequests\ResponseNotificationHandler as AccessRequestResponseNotificationHandler;
use prime\jobHandlers\permissions\CheckImplicitAccessRequestGrantedHandler as PermissionCheckImplicitAccessRequestGrantedHandler;
use prime\jobHandlers\users\SyncNewsletterSubscriptionHandler as UserSyncNewsletterSubscriptionHandler;
use prime\jobs\accessRequests\CreatedNotificationJob as AccessRequestCreatedNotificationJob;
use prime\jobs\accessRequests\ImplicitlyGrantedNotificationJob as AccessrequestImplicitlyGrantedJob;
use prime\jobs\accessRequests\ResponseNotificationJob as AccessRequestResponseNotificationJob;
use prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob as PermissionCheckImplicitAccessRequestGrantedJob;
use prime\jobs\users\SyncNewsletterSubscriptionJob as UserSyncNewsletterSubscriptionJob;
use prime\models\ar\Permission;
use prime\objects\enums\Language;
use prime\repositories\AccessRequestRepository as AccessRequestARRepository;
use prime\repositories\FacilityRepository;
use prime\repositories\PermissionRepository as PermissionARRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\ResponseForLimesurveyRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\SurveyResponseRepository;
use prime\repositories\UserNotificationRepository;
use prime\repositories\UserRepository;
use prime\repositories\WorkspaceRepository;
use prime\widgets\LocalizableInput;
use SamIT\abac\engines\SimpleEngine;
use SamIT\abac\interfaces\PermissionRepository;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\interfaces\RuleEngine;
use SamIT\abac\repositories\CachedReadRepository;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\abac\resolvers\ChainedResolver;
use SamIT\Yii2\abac\ActiveRecordRepository;
use SamIT\Yii2\abac\ActiveRecordResolver;
use yii\behaviors\TimestampBehavior;
use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\db\Expression;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\mail\MailerInterface;
use yii\web\JqueryAsset;

assert(isset($env) && $env instanceof \prime\interfaces\EnvironmentInterface);

return [
    AuditableBehavior::class => static function () {
        return new AuditableBehavior(\Yii::$app->auditService);
    },
    \Psr\Http\Client\ClientInterface::class => \GuzzleHttp\Client::class,
    \Psr\Http\Message\RequestFactoryInterface::class => \Http\Factory\Guzzle\RequestFactory::class,
    \prime\helpers\ModelHydrator::class => \prime\helpers\ModelHydrator::class,
    LocalizableInput::class => function (Container $container, array $params, array $config) {
        if (!isset($config['languages'])) {
            $config['languages'] = Language::toLocalizedArrayWithoutSourceLanguage(\Yii::$app->language);
        }
        return new LocalizableInput($config);
    },
    Dialog::class => \yii\base\Widget::class,
    AccessCheckInterface::class => AccessCheck::class,
    AccessCheck::class => static function () {
        return new AccessCheck(\Yii::$app->user);
    },
    LimesurveyDataLoader::class => LimesurveyDataLoader::class,
    JqueryAsset::class => JqueryBundle::class,
    PermissionRepository::class => PreloadingSourceRepository::class,
    PreloadingSourceRepository::class =>
        fn (Container $container) => new PreloadingSourceRepository($container->get(CachedReadRepository::class)),
    CachedReadRepository::class => function (Container $container) {
        return new CachedReadRepository($container->get(ActiveRecordRepository::class));
    },
    RuleEngine::class => static fn() => new SimpleEngine(require __DIR__ . '/rule-config.php'),
    Resolver::class => static function (): Resolver {
        return new ChainedResolver(
            new SingleTableInheritanceResolver(),
            new ReadWriteModelResolver(),
            new ActiveRecordResolver(),
            new GlobalPermissionResolver()
        );
    },
    ProjectRepository::class => ProjectRepository::class,
    WorkspaceRepository::class => WorkspaceRepository::class,
    FacilityRepository::class => FacilityRepository::class,
    ResponseForLimesurveyRepository::class => ResponseForLimesurveyRepository::class,
    SurveyRepository::class => SurveyRepository::class,
    SurveyResponseRepository::class => SurveyResponseRepository::class,
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
        return new ActionColumn($config);
    },
    SwitchInput::class => static function (Container $container, array $params, array $config) {
        $config = ArrayHelper::merge([
            'pluginOptions' => [
                'offText' => \Yii::t('app', 'Off'),
                'onText' => \Yii::t('app', 'On'),
            ]
        ], $config);
        return new SwitchInput($config);
    },
    GridView::class => static function (Container $container, array $params, array $config): GridView {

        $result = new GridView(array_merge([
            'dataColumnClass' => \prime\widgets\FunctionGetterDataColumn::class,
        ], $config));
        $result->export = false;
        $result->toggleData = false;
        return $result;
    },
    UserNotificationRepository::class => static function (Container $container, array $params, array $config): UserNotificationRepository {
        $result = new UserNotificationRepository(\Yii::$app->abacManager, $container->get(AccessRequestARRepository::class));
        return $result;
    },
    CommandBus::class => function (Container $container) {
        return new CommandBus([
            new \prime\helpers\LoggingMiddleware(),
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
            ->setHandlerForCommand(UserSyncNewsletterSubscriptionJob::class, UserSyncNewsletterSubscriptionHandler::class)
            ;
    },
    CommandNameExtractor::class => ClassNameExtractor::class,
    EventDispatcherInterface::class => EventDispatcherProxy::class,
    HandlerLocator::class => ContainerMapLocator::class,
    JobFactoryInterface::class => JobFactory::class,
    JobQueueInterface::class => Synchronous::class,
    MethodNameInflector::class => HandleInflector::class,
    MailerInterface::class => static function (Container $container, array $params, array $config): MailerInterface {
        return \Yii::$app->mailer;
    },
    CacheInterface::class => FileCache::class,
    PermissionCheckImplicitAccessRequestGrantedHandler::class => static function (Container $container, array $params, array $config): PermissionCheckImplicitAccessRequestGrantedHandler {
        return new PermissionCheckImplicitAccessRequestGrantedHandler(
            \Yii::$app->abacManager,
            $container->get(AccessRequestARRepository::class),
            $container->get(JobQueueInterface::class),
            $container->get(PermissionARRepository::class),
            $container->get(Resolver::class),
        );
    },
    MailChimp::class => static function (Container $container, array $params, array $config) use ($env): MailChimp {
        $apiKey = empty((string) $env->getWrappedSecret('mailchimp/api_key')) ? '-' : $env->getWrappedSecret('mailchimp/api_key');
        return new MailChimp($apiKey);
    },
    NewsletterService::class => [
        'class' => NewsletterService::class,
        'mailchimpListId' => $env->getWrappedSecret('mailchimp/list_id'),
        'mailchimpTag' => $env->getWrappedSecret('mailchimp/tag'),
    ],
    TimestampBehavior::class => [
        'class' => TimestampBehavior::class,
        'value' => new Expression('NOW()'),
    ],
    UserRepository::class => UserRepository::class,
];
