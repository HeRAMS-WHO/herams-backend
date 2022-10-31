<?php

declare(strict_types=1);

use prime\components\AuditService;
use prime\components\Formatter;
use prime\components\JobSubmissionService;
use prime\interfaces\EnvironmentInterface;
use yii\web\GroupUrlRule;
use yii\web\UrlRule;

return function(EnvironmentInterface $env, \yii\di\Container $container) : array {
    $diConfigurator = require __DIR__ . '/di.php';
    $diConfigurator($env, $container);
    $config = yii\helpers\ArrayHelper::merge(require(__DIR__ . '/../../../protected/config/common.php'), [
        'controllerNamespace' => 'herams\\api\\controllers',
        'bootstrap' => [
            JobSubmissionService::class,
            'auditService',
        ],
        'defaultRoute' => 'marketplace/herams',
        'components' => [
            'jobQueue' => \JCIT\jobqueue\interfaces\JobQueueInterface::class,
            'auditService' => AuditService::class,
            'formatter' => [
                'class' => Formatter::class,
            ],
            'urlManager' => [
                'class' => \yii\web\UrlManager::class,
                'cache' => false,
                'enableStrictParsing' => true,
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => [
                    [
                        'class' => GroupUrlRule::class,
                        'prefix' => 'api',
                        'routePrefix' => '',
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
                                'class' => UrlRule::class,
                                'pattern' => 'user/<id:\d+>/workspaces',
                                'route' => 'user/workspaces',
                                'verb' => ['delete', 'put'],
                            ],
                            [
                                'pattern' => '<p:.*>',
                                'route' => '',
                            ],
                        ],
                    ]
                ]
            ],
            'user' => [
                'class' => \yii\web\User::class,
                'enableSession' => false,
                'loginUrl' => null,

            ],
            'request' => [
                'class' => \yii\web\Request::class,
                'trustedHosts' => [
                    '10.0.0.0/8',
                    '172.0.0.0/8',
                ],
                'cookieValidationKey' => $env->getWrappedSecret('app/cookie_validation_key'),
                // To enable rendering in tests.
                'scriptFile' => realpath(__DIR__ . '/../../public/index.php'),
                'scriptUrl' => '/',
                'parsers' => [
                    'application/json' => yii\web\JsonParser::class,
                ],
            ],
            'response' => [
                'class' => \yii\web\Response::class,
                'formatters' => [
                    \yii\web\Response::FORMAT_JSON => [
                        'class' => \yii\web\JsonResponseFormatter::class,
                        'prettyPrint' => true,
                    ],
                ],
            ],
//        'assetManager' => [
//            'class' => \herams\api\components\DummyAssetManager::class
//
//        ],
        ],
    ]);

    if (YII_DEBUG && file_exists(__DIR__ . '/../../../protected/config/debug.php')) {
        $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/../../../protected/config/debug.php'));
    }

    $container->set(\yii\web\Application::class, $config);
    return $config;

};
