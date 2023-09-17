<?php

declare(strict_types=1);

namespace herams\common\config;

use ArrayObject;
use herams\common\components\AuditService;
use herams\common\components\LazyUrlFactory;
use herams\common\components\RewriteRule;
use herams\common\domain\accessRequest\AccessRequestRepository;
use herams\common\domain\facility\FacilityHydrator;
use herams\common\domain\favorite\FavoriteRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\BaseClassResolver;
use herams\common\helpers\CommandFactory;
use herams\common\helpers\ConfigurationProvider;
use herams\common\helpers\CurrentUserIdProvider;
use herams\common\helpers\EventDispatcherProxy;
use herams\common\helpers\GlobalPermissionResolver;
use herams\common\helpers\LoggingMiddleware;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\SingleTableInheritanceResolver;
use herams\common\helpers\StrategyActiveRecordHydrator;
use herams\common\helpers\surveyjs\SurveyParser;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\AuditServiceInterface;
use herams\common\interfaces\CommandFactoryInterface;
use herams\common\interfaces\ContainerConfiguratorInterface;
use herams\common\interfaces\CurrentUserIdProviderInterface;
use herams\common\interfaces\EnvironmentInterface;
use herams\common\interfaces\EventDispatcherInterface;
use herams\common\interfaces\ModelHydratorInterface;
use herams\common\interfaces\SurveyRepositoryInterface;
use herams\common\jobHandlers\accessRequests\CreatedNotificationHandler;
use herams\common\jobHandlers\accessRequests\ImplicitlyGrantedNotificationHandler;
use herams\common\jobHandlers\accessRequests\ResponseNotificationHandler;
use herams\common\jobHandlers\permissions\CheckImplicitAccessRequestGrantedHandler;
use herams\common\jobHandlers\UpdateFacilityDataHandler;
use herams\common\jobHandlers\users\SyncNewsletterSubscriptionHandler;
use herams\common\jobs\accessRequests\CreatedNotificationJob;
use herams\common\jobs\accessRequests\ImplicitlyGrantedNotificationJob;
use herams\common\jobs\accessRequests\ResponseNotificationJob;
use herams\common\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use herams\common\jobs\UpdateFacilityDataJob;
use herams\common\jobs\users\SyncNewsletterSubscriptionJob;
use herams\common\models\PermissionOld;
use herams\common\services\UserAccessCheck;
use herams\common\utils\tools\SurveyParserClean;
use Http\Factory\Guzzle\UriFactory;
use JCIT\jobqueue\components\jobQueues\Synchronous;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UriFactoryInterface;
use SamIT\abac\engines\SimpleEngine;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\PermissionRepository;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\interfaces\RuleEngine;
use SamIT\abac\repositories\CachedReadRepository;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\abac\resolvers\ChainedResolver;
use SamIT\Yii2\abac\ActiveRecordRepository;
use SamIT\Yii2\abac\ActiveRecordResolver;
use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\db\Connection;
use yii\di\Container;
use yii\di\Instance;
use yii\web\UrlManager;

class CommonConfigurator implements ContainerConfiguratorInterface
{
    public function configure(EnvironmentInterface $environment, Container $container): void
    {
        $container->set(\Lcobucci\JWT\Configuration::class, static function () use ($environment): Configuration {
            $key = InMemory::plainText($environment->getSecret('app/jwt_secret_key'));
            $result = Configuration::forSymmetricSigner(
                new Sha256(),
                $key
            );
            $result->setValidationConstraints(
                new SignedWith($result->signer(), $result->signingKey()),
                new IssuedBy('https://app.herams.org'),
                new PermittedFor('https://api.herams.org'),
                new StrictValidAt(SystemClock::fromUTC())
            );
            return $result;
        });
        $container->set(Environment::class, new class() extends ArrayObject implements Environment {
        });
        $container->set(ConfigurationProvider::class);
        $container->set(Connection::class, [
            'charset' => 'utf8mb4',
            'dsn' => $environment->getSecret('database/dsn'),
            'password' => $environment->getWrappedSecret('database/password'),
            'username' => $environment->getWrappedSecret('database/username'),
            'enableSchemaCache' => ! YII_DEBUG,
            'schemaCache' => 'cache',
            'enableQueryCache' => true,
            'queryCache' => 'cache',
            'tablePrefix' => 'prime2_',
        ]);
        $container->set(RewriteRule::class, static function (Container $container) {
            $frontend = new LazyUrlFactory(Instance::of('frontendUrlManager'), $container, null);

            $api = new LazyUrlFactory(Instance::of('apiUrlManager'), $container, '/api-proxy/core');
            return new RewriteRule($api, $frontend);
        });
        $container->set('apiUrlManager', [
            'class' => \yii\web\UrlManager::class,
            'cache' => false,
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require __DIR__ . '/routes-api.php',
        ]);
        $container->set('frontendUrlManager', [
            'class' => UrlManager::class,
            'cache' => false,
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require __DIR__ . '/routes-frontend.php',
        ]);

        $container->set(EventDispatcherInterface::class, EventDispatcherProxy::class);
        $container->set(CacheInterface::class, FileCache::class);
        $container->set(AuditServiceInterface::class, AuditService::class);
        $container->set(WorkspaceRepository::class, WorkspaceRepository::class);
        $container->set(AccessCheckInterface::class, static fn () => new UserAccessCheck(\Yii::$app->user));
        $container->set(SurveyParserClean::class, SurveyParserClean::class);
        $container->set(SurveyRepository::class, SurveyRepository::class);
        $container->set(SurveyParser::class, SurveyParser::class);
        $container->set(AccessRequestRepository::class, AccessRequestRepository::class);
        $container->set(FavoriteRepository::class, FavoriteRepository::class);
        $container->set(ActiveRecordHydratorInterface::class, static function (): ActiveRecordHydratorInterface {
            $result = new StrategyActiveRecordHydrator();

            $result->registerAttributeStrategy(new FacilityHydrator());
            $result->registerAttributeStrategy(new ModelHydrator());
            return $result;
        });
        $container->set(Resolver::class, static function (): Resolver {
            return new ChainedResolver(
                new SingleTableInheritanceResolver(),
                new GlobalPermissionResolver(),
                new BaseClassResolver(),
                new ActiveRecordResolver(),
            );
        });

        $container->set(ModelHydratorInterface::class, ModelHydrator::class);
        $container->set(ProjectRepository::class, ProjectRepository::class);
        $container->set(RuleEngine::class, static fn () => new SimpleEngine(...require __DIR__ . '/rule-config.php'));
        $container->setDefinitions([
            UriFactoryInterface::class => UriFactory::class,
            JobQueueInterface::class => Synchronous::class,
            PermissionRepository::class => PreloadingSourceRepository::class,
            PreloadingSourceRepository::class => fn (Container $container) => new PreloadingSourceRepository($container->get(CachedReadRepository::class)),
            CachedReadRepository::class => function (Container $container) {
                return new CachedReadRepository($container->get(ActiveRecordRepository::class));
            },
            ActiveRecordRepository::class => static function () {
                return new ActiveRecordRepository(PermissionOld::class, [
                    ActiveRecordRepository::SOURCE_ID => ActiveRecordRepository::SOURCE_ID,
                    ActiveRecordRepository::SOURCE_NAME => 'source',
                    ActiveRecordRepository::TARGET_ID => ActiveRecordRepository::TARGET_ID,
                    ActiveRecordRepository::TARGET_NAME => 'target',
                    ActiveRecordRepository::PERMISSION => ActiveRecordRepository::PERMISSION,
                ]);
            },
            CurrentUserIdProviderInterface::class => CurrentUserIdProvider::class,
            CommandFactoryInterface::class => CommandFactory::class,
            CommandBus::class => function (Container $container) {
                return new CommandBus([
                    new LoggingMiddleware(),
                    new CommandHandlerMiddleware(
                        $container->get(CommandNameExtractor::class),
                        $container->get(HandlerLocator::class),
                        $container->get(MethodNameInflector::class)
                    ),
                ]);
            },
            CommandNameExtractor::class => ClassNameExtractor::class,
            HandlerLocator::class => ContainerLocator::class,
            ContainerInterface::class => fn (Container $container) => new class($container) implements ContainerInterface {
                public function __construct(
                    private readonly Container $container
                ) {
                }

                /**
                 * Not fully compliant since we don't throw proper exception types. For now this is OK
                 */
                public function get(string $id): mixed
                {
                    return $this->container->get($id);
                }

                public function has(string $id): bool
                {
                    return $this->container->has($id) || $this->container->hasSingleton($id);
                }
            },
            ContainerLocator::class => fn (Container $container) => new ContainerLocator($container->get(ContainerInterface::class), [
                CreatedNotificationJob::class => CreatedNotificationHandler::class,
                ImplicitlyGrantedNotificationJob::class => ImplicitlyGrantedNotificationHandler::class,
                ResponseNotificationJob::class => ResponseNotificationHandler::class,
                CheckImplicitAccessRequestGrantedJob::class => CheckImplicitAccessRequestGrantedHandler::class,
                SyncNewsletterSubscriptionJob::class => SyncNewsletterSubscriptionHandler::class,
                UpdateFacilityDataJob::class => UpdateFacilityDataHandler::class,
            ]),
            MethodNameInflector::class => HandleInflector::class,
            SurveyRepositoryInterface::class => SurveyRepository::class,
        ]);

        return;
    }
}
