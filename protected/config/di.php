<?php

declare(strict_types=1);

use Collecthor\Yii2SessionAuth\IdentityFinderInterface;
use Collecthor\Yii2SessionAuth\IdentityInterfaceIdentityFinder;
use DrewM\MailChimp\MailChimp;
use GuzzleHttp\Client;
use herams\common\behaviors\AuditableBehavior;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\permission\PermissionRepository as PermissionARRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\user\User;
use herams\common\domain\user\UserRepository;
use herams\common\domain\variableSet\HeramsVariableSetRepository;
use herams\common\enums\Language;
use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\EnvironmentInterface;
use herams\common\interfaces\ModelHydratorInterface;
use herams\common\jobs\accessRequests\CreatedNotificationJob as AccessRequestCreatedNotificationJob;
use herams\common\jobs\accessRequests\ImplicitlyGrantedNotificationJob as AccessrequestImplicitlyGrantedJob;
use herams\common\jobs\accessRequests\ResponseNotificationJob as AccessRequestResponseNotificationJob;
use herams\common\jobs\permissions\CheckImplicitAccessRequestGrantedJob as PermissionCheckImplicitAccessRequestGrantedJob;
use herams\common\jobs\users\SyncNewsletterSubscriptionJob as UserSyncNewsletterSubscriptionJob;
use Http\Factory\Guzzle\RequestFactory;
use JCIT\jobqueue\components\ContainerMapLocator;
use JCIT\jobqueue\factories\JobFactory;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use prime\assets\JqueryBundle;
use prime\components\ApiProxy;
use prime\components\NewsletterService;
use prime\repositories\AccessRequestRepository as AccessRequestARRepository;
use prime\repositories\UserNotificationRepository;
use prime\widgets\LocalizableInput;
use SamIT\abac\interfaces\Resolver;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\mail\MailerInterface;
use yii\web\JqueryAsset;
use yii\widgets\PjaxAsset;

assert(isset($env) && $env instanceof EnvironmentInterface);

return [
    \prime\repositories\FormRepository::class => \prime\repositories\FormRepository::class,
    PjaxAsset::class => [
        'baseUrl' => '@npm/yii2-pjax',
        'sourcePath' => null,
    ],
    ModelHydrator::class => ModelHydrator::class,
    \prime\components\BreadcrumbService::class => \prime\components\BreadcrumbService::class,

    ModelHydratorInterface::class => ModelHydrator::class,
    \kartik\form\ActiveField::class => static function (Container $container, array $params, array $config) {
        $result = new \prime\components\ActiveField($config);
        return $result;
    },
    AuditableBehavior::class => static function () {
        return new AuditableBehavior(\Yii::$app->auditService);
    },
    ApiProxy::class => ApiProxy::class,
    \prime\objects\ApiConfiguration::class => static fn () => new \prime\objects\ApiConfiguration($env->getWithDefault('API_HOST', 'api.herams.test')),
    \yii\web\Session::class => fn () => \Yii::$app->session,
    IdentityFinderInterface::class => new IdentityInterfaceIdentityFinder(User::class),

    \prime\repositories\ElementRepository::class => \prime\repositories\ElementRepository::class,
    \herams\common\interfaces\HeramsVariableSetRepositoryInterface::class => HeramsVariableSetRepository::class,
    \herams\common\domain\project\ProjectLocalesRetriever::class => ProjectRepository::class,
    \Psr\Http\Client\ClientInterface::class => static function (Container $container) {
        return new Client([
            'verify' => false,
            //            'curl' => [
            //                CURLOPT_SSL_VERIFYPEER
            //            ]
        ]);
    },
    \Psr\Http\Message\RequestFactoryInterface::class => RequestFactory::class,
    \herams\common\helpers\ModelValidator::class => \herams\common\helpers\ModelValidator::class,
    LocalizableInput::class => function (Container $container, array $params, array $config) {
        if (! isset($config['languages'])) {
            $config['languages'] = Language::toLocalizedArrayWithoutSourceLanguage(Language::from(\Yii::$app->language));
        }
        return new LocalizableInput($config);
    },
    Dialog::class => \yii\base\Widget::class,
    JqueryAsset::class => JqueryBundle::class,
    \herams\common\domain\permission\PermissionRepository::class => \herams\common\domain\permission\PermissionRepository::class,
    ProjectRepository::class => ProjectRepository::class,
    FacilityRepository::class => FacilityRepository::class,
    \herams\common\domain\page\PageRepository::class => \herams\common\domain\page\PageRepository::class,
    SurveyResponseRepository::class => SurveyResponseRepository::class,

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

    ContainerMapLocator::class => function (Container $container) {
        return (new ContainerMapLocator($container))
            ->setHandlerForCommand(AccessRequestCreatedNotificationJob::class, AccessRequestCreatedNotificationHandler::class)
            ->setHandlerForCommand(AccessrequestImplicitlyGrantedJob::class, AccessRequestImplicitlyGrantedHandler::class)
            ->setHandlerForCommand(AccessRequestResponseNotificationJob::class, AccessRequestResponseNotificationHandler::class)
            ->setHandlerForCommand(PermissionCheckImplicitAccessRequestGrantedJob::class, PermissionCheckImplicitAccessRequestGrantedHandler::class)
            ->setHandlerForCommand(UserSyncNewsletterSubscriptionJob::class, UserSyncNewsletterSubscriptionHandler::class)
            ->setHandlerForCommand(\herams\common\jobs\UpdateFacilityDataJob::class, UpdateFacilityDataHandler::class)
        ;
    },

    JobFactoryInterface::class => JobFactory::class,
    MailerInterface::class => static function (Container $container, array $params, array $config): MailerInterface {
        return \Yii::$app->mailer;
    },
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
