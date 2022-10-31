<?php

declare(strict_types=1);

use Collecthor\SurveyjsParser\SurveyParser;
use Collecthor\Yii2SessionAuth\IdentityFinderInterface;
use Collecthor\Yii2SessionAuth\IdentityInterfaceIdentityFinder;
use DrewM\MailChimp\MailChimp;
use GuzzleHttp\Client;
use Http\Factory\Guzzle\RequestFactory;
use JCIT\jobqueue\components\ContainerMapLocator;
use JCIT\jobqueue\components\jobQueues\Synchronous;
use JCIT\jobqueue\factories\JobFactory;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use Lcobucci\JWT\Configuration;
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
use prime\helpers\EventDispatcherProxy;
use prime\helpers\ModelHydrator;
use prime\helpers\StrategyActiveRecordHydrator;
use prime\helpers\UserAccessCheck;
use prime\hydrators\FacilityHydrator;
use prime\hydrators\ProjectHydrator;
use prime\hydrators\WorkspaceHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\EnvironmentInterface;
use prime\interfaces\EventDispatcherInterface;
use prime\interfaces\ModelHydratorInterface;
use prime\jobHandlers\accessRequests\CreatedNotificationHandler as AccessRequestCreatedNotificationHandler;
use prime\jobHandlers\accessRequests\ImplicitlyGrantedNotificationHandler as AccessRequestImplicitlyGrantedHandler;
use prime\jobHandlers\accessRequests\ResponseNotificationHandler as AccessRequestResponseNotificationHandler;
use prime\jobHandlers\permissions\CheckImplicitAccessRequestGrantedHandler as PermissionCheckImplicitAccessRequestGrantedHandler;
use prime\jobHandlers\UpdateFacilityDataHandler;
use prime\jobHandlers\users\SyncNewsletterSubscriptionHandler as UserSyncNewsletterSubscriptionHandler;
use prime\jobs\accessRequests\CreatedNotificationJob as AccessRequestCreatedNotificationJob;
use prime\jobs\accessRequests\ImplicitlyGrantedNotificationJob as AccessrequestImplicitlyGrantedJob;
use prime\jobs\accessRequests\ResponseNotificationJob as AccessRequestResponseNotificationJob;
use prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob as PermissionCheckImplicitAccessRequestGrantedJob;
use prime\jobs\users\SyncNewsletterSubscriptionJob as UserSyncNewsletterSubscriptionJob;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\objects\enums\Language;
use prime\repositories\AccessRequestRepository as AccessRequestARRepository;
use prime\repositories\FacilityRepository;
use prime\repositories\HeramsVariableSetRepository;
use prime\repositories\PermissionRepository as PermissionARRepository;
use prime\repositories\ProjectRepository;
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

assert(isset($env) && $env instanceof EnvironmentInterface);

return [
    \prime\helpers\ConfigurationProvider::class => \prime\helpers\ConfigurationProvider::class,
    \yii\widgets\PjaxAsset::class => [
        'baseUrl' => '@npm/yii2-pjax',
        'sourcePath' => null,
    ],
    Configuration::class => static function() use ($env): Configuration {
        $result = Configuration::forSymmetricSigner(
            new \Lcobucci\JWT\Signer\Hmac\Sha256(),
            \Lcobucci\JWT\Signer\Key\InMemory::plainText('secretsecretsecretsecretsecretsecretsecretsecretsecret' ?? $env->getSecret('app/sso_private_key'))
        );
        $result->setValidationConstraints(new \Lcobucci\JWT\Validation\Constraint\SignedWith($result->signer(), $result->signingKey()));
        return $result;
    },
    \prime\components\BreadcrumbService::class => \prime\components\BreadcrumbService::class,
    ActiveRecordHydratorInterface::class => static function (): ActiveRecordHydratorInterface {
        $result = new StrategyActiveRecordHydrator();
        $result->registerAttributeStrategy(new WorkspaceHydrator());
        $result->registerAttributeStrategy(new ProjectHydrator());
        $result->registerAttributeStrategy(new FacilityHydrator());
        $result->registerAttributeStrategy(new ModelHydrator());
        return $result;
    },
    ModelHydratorInterface::class => ModelHydrator::class,
    \kartik\form\ActiveField::class => static function (Container $container, array $params, array $config) {
        $result = new \prime\components\ActiveField($config);
        return $result;
    },
    AuditableBehavior::class => static function () {
        return new AuditableBehavior(\Yii::$app->auditService);
    },
    \prime\components\ApiProxy::class => \prime\components\ApiProxy::class,
    \yii\web\Session::class => fn () => \Yii::$app->session,
    IdentityFinderInterface::class => new IdentityInterfaceIdentityFinder(User::class),
    \prime\interfaces\SurveyRepositoryInterface::class => SurveyRepository::class,
    \prime\repositories\ElementRepository::class => \prime\repositories\ElementRepository::class,
    \prime\interfaces\HeramsVariableSetRepositoryInterface::class => HeramsVariableSetRepository::class,
    \prime\interfaces\project\ProjectLocalesRetriever::class => ProjectRepository::class,
    SurveyParser::class => \prime\helpers\SurveyParser::class,
    \Psr\Http\Client\ClientInterface::class => static function(Container $container) {
        return new Client([
            'verify' => false
//            'curl' => [
//                CURLOPT_SSL_VERIFYPEER
//            ]
        ]);
    },
    \Psr\Http\Message\RequestFactoryInterface::class => RequestFactory::class,
    ModelHydrator::class => ModelHydrator::class,
    \prime\helpers\ModelValidator::class => \prime\helpers\ModelValidator::class,
    LocalizableInput::class => function (Container $container, array $params, array $config) {
        if (! isset($config['languages'])) {
            $config['languages'] = Language::toLocalizedArrayWithoutSourceLanguage(Language::from(\Yii::$app->language));
        }
        return new LocalizableInput($config);
    },
    Dialog::class => \yii\base\Widget::class,
    AccessCheckInterface::class => UserAccessCheck::class,
    UserAccessCheck::class => static function () {
        return new UserAccessCheck(\Yii::$app->user);
    },
    JqueryAsset::class => JqueryBundle::class,
    \prime\repositories\PermissionRepository::class => \prime\repositories\PermissionRepository::class,
    PermissionRepository::class => PreloadingSourceRepository::class,
    PreloadingSourceRepository::class =>
        fn (Container $container) => new PreloadingSourceRepository($container->get(CachedReadRepository::class)),
    CachedReadRepository::class => function (Container $container) {
        return new CachedReadRepository($container->get(ActiveRecordRepository::class));
    },
    RuleEngine::class => static fn () => new SimpleEngine(...require __DIR__ . '/rule-config.php'),
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
    \prime\repositories\PageRepository::class => \prime\repositories\PageRepository::class,
    SurveyRepository::class => SurveyRepository::class,
    SurveyResponseRepository::class => SurveyResponseRepository::class,
    ActiveRecordRepository::class => static function () {
        return new ActiveRecordRepository(Permission::class, [
            ActiveRecordRepository::SOURCE_ID => ActiveRecordRepository::SOURCE_ID,
            ActiveRecordRepository::SOURCE_NAME => 'source',
            ActiveRecordRepository::TARGET_ID => ActiveRecordRepository::TARGET_ID,
            ActiveRecordRepository::TARGET_NAME => 'target',
            ActiveRecordRepository::PERMISSION => ActiveRecordRepository::PERMISSION,
        ]);
    },
    ActionColumn::class => static function (Container $container, array $params = [], array $config = []): \yii\grid\ActionColumn {
        if (! isset($config['header'])) {
            $config['header'] = \Yii::t('app', 'Actions');
        }
        return new ActionColumn($config);
    },
    SwitchInput::class => static function (Container $container, array $params, array $config) {
        $config = ArrayHelper::merge([
            'pluginOptions' => [
                'offText' => \Yii::t('app', 'Off'),
                'onText' => \Yii::t('app', 'On'),
            ],
        ], $config);
        return new SwitchInput($config);
    },
    GridView::class => static function (Container $container, array $params, array $config): GridView {
        $result = new GridView([
            'dataColumnClass' => \prime\widgets\DataColumn::class,
            ...$config,
        ]);
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
            ),
        ]);
    },
    ContainerMapLocator::class => function (Container $container) {
        return (new ContainerMapLocator($container))
            ->setHandlerForCommand(AccessRequestCreatedNotificationJob::class, AccessRequestCreatedNotificationHandler::class)
            ->setHandlerForCommand(AccessrequestImplicitlyGrantedJob::class, AccessRequestImplicitlyGrantedHandler::class)
            ->setHandlerForCommand(AccessRequestResponseNotificationJob::class, AccessRequestResponseNotificationHandler::class)
            ->setHandlerForCommand(PermissionCheckImplicitAccessRequestGrantedJob::class, PermissionCheckImplicitAccessRequestGrantedHandler::class)
            ->setHandlerForCommand(UserSyncNewsletterSubscriptionJob::class, UserSyncNewsletterSubscriptionHandler::class)
            ->setHandlerForCommand(\prime\jobs\UpdateFacilityDataJob::class, UpdateFacilityDataHandler::class)
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
