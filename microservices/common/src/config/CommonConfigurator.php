<?php
declare(strict_types=1);

namespace herams\common\config;

use herams\common\components\AuditService;
use herams\common\domain\facility\FacilityHydrator;
use herams\common\domain\project\ProjectHydrator;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceHydrator;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\BaseClassResolver;
use herams\common\helpers\EventDispatcherProxy;
use herams\common\helpers\GlobalPermissionResolver;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ReadWriteModelResolver;
use herams\common\helpers\SingleTableInheritanceResolver;
use herams\common\helpers\StrategyActiveRecordHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\AuditServiceInterface;
use herams\common\interfaces\ContainerConfiguratorInterface;
use herams\common\interfaces\EnvironmentInterface;
use herams\common\interfaces\EventDispatcherInterface;
use herams\common\interfaces\ModelHydratorInterface;
use herams\common\models\Permission;
use herams\common\services\UserAccessCheck;
use SamIT\abac\engines\SimpleEngine;
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
use yii\di\Container;
use yii\web\UrlRule;

class CommonConfigurator implements ContainerConfiguratorInterface
{

    public function configure(EnvironmentInterface $environment, Container $container): void
    {
        $container->set('apiUrlManager', [
            'class' => \yii\web\UrlManager::class,
            'cache' => false,
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'baseUrl' => '/api-proxy/core',
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:[\w-]+>',
                    'route' => '<controller>/create',
                    'verb' => 'POST',
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => 'permission/grant',
                    'route' => 'permission/grant',
                    'verb' => ['put', 'delete'],
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/<id:\d+>/validate',
                    'route' => '<controller>/validate',
                    'verb' => ['post'],
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/validate',
                    'route' => '<controller>/validate',
                    'verb' => ['post'],
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/<id:\d+>/<action:\w+>/<target_id:\d+>',
                    'route' => '<controller>/<action>',
                    'verb' => ['put', 'delete'],
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view',
                    'verb' => 'get',
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/<id:\d+>',
                    'route' => '<controller>/update',
                    'verb' => ['post'],
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/<id:\d+>',
                    'route' => '<controller>/delete',
                    'verb' => ['delete'],
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/<id:\d+>/<action:[\w-]+>',
                    'route' => '<controller>/<action>',
                    'verb' => ['get', 'post'],
                ],

                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>s',
                    'verb' => 'get',
                    'route' => '<controller>/index',
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => 'user/<id:\d+>/workspaces',
                    'route' => 'user/workspaces',
                    'verb' => ['delete', 'put'],
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/<action:[\w-]+>',
                    'verb' => 'get',
                    'route' => '<controller>/<action>',
                ],

                [
                    'class' => UrlRule::class,
                    'pattern' => 'response',
                    'route' => 'response/update',
                    'verb' => 'post',
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => 'configuration/<action:\w+>',
                    'route' => 'configuration/<action>',
                    'verb' => 'get',
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => 'response',
                    'route' => 'response/delete',
                    'verb' => 'delete',
                ],
                [
                    'pattern' => '<p:.*>',
                    'route' => '',
                ],
            ],
        ]);

        $container->set(EventDispatcherInterface::class, EventDispatcherProxy::class);
        $container->set(CacheInterface::class, FileCache::class);
        $container->set(AuditServiceInterface::class, AuditService::class);
        $container->set(WorkspaceRepository::class, WorkspaceRepository::class);
        $container->set(AccessCheckInterface::class, UserAccessCheck::class);
        $container->set(SurveyRepository::class, SurveyRepository::class);

        $container->set(ActiveRecordHydratorInterface::class, static function (): ActiveRecordHydratorInterface {
            $result = new StrategyActiveRecordHydrator();
            $result->registerAttributeStrategy(new WorkspaceHydrator());
            $result->registerAttributeStrategy(new ProjectHydrator());
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

        // This defines a service that depends on an application component.
        $container->set(UserAccessCheck::class, static fn() => new UserAccessCheck(\Yii::$app->user));

        $container->set(ModelHydratorInterface::class, ModelHydrator::class);
        $container->set(ProjectRepository::class, ProjectRepository::class);
        $container->set(RuleEngine::class, static fn () => new SimpleEngine(...require __DIR__ . '/rule-config.php'));
        $container->setDefinitions([
            PermissionRepository::class => PreloadingSourceRepository::class,
            PreloadingSourceRepository::class => fn (Container $container) => new PreloadingSourceRepository($container->get(CachedReadRepository::class)),
            CachedReadRepository::class => function (Container $container) {
                return new CachedReadRepository($container->get(ActiveRecordRepository::class));
            },
            ActiveRecordRepository::class => static function () {
                return new ActiveRecordRepository(Permission::class, [
                    ActiveRecordRepository::SOURCE_ID => ActiveRecordRepository::SOURCE_ID,
                    ActiveRecordRepository::SOURCE_NAME => 'source',
                    ActiveRecordRepository::TARGET_ID => ActiveRecordRepository::TARGET_ID,
                    ActiveRecordRepository::TARGET_NAME => 'target',
                    ActiveRecordRepository::PERMISSION => ActiveRecordRepository::PERMISSION,
                ]);
            },
            ]);

        return ;
    }



}
