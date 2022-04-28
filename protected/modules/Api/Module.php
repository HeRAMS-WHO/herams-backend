<?php

declare(strict_types=1);

namespace prime\modules\Api;

use Collecthor\Yii2SessionAuth\SessionAuth;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\web\GroupUrlRule;
use yii\web\Response;
use yii\web\UrlRule;

/**
 * @property \yii\web\User $user
 */
class Module extends \yii\base\Module
{
    public static function urlRules(): array
    {
        return [
            'class' => GroupUrlRule::class,
            'prefix' => 'api',
            'rules' => [
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/<id:\d+>/<action:\w+>/<target_id:\d+>',
                    'route' => '<controller>/<action>',
                    'verb' => ['put', 'delete']
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view',
                    'verb' => 'get'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>/<id:\d+>/<action:[\w-]+>',
                    'route' => '<controller>/<action>',
                    'verb' => ['get', 'post']
                ],

                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>s',
                    'verb' => 'get',
                    'route' => '<controller>/index'
                ],



                [
                    'class' => UrlRule::class,
                    'pattern' => 'response',
                    'route' => 'response/update',
                    'verb' => 'post'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => 'response',
                    'route' => 'response/delete',
                    'verb' => 'delete'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>',
                    'route' => '<controller>/create',
                    'verb' => 'POST'
                ],
                [
                    'pattern' => '<p:.*>',
                    'route' => '',
                ]
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->user->enableSession = false;
        return parent::beforeAction($action);
    }


    public function behaviors()
    {
        return [
            ContentNegotiator::class => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ],
            'authenticator' => [
                'class' => CompositeAuth::class,
                'optional' => ['*'],
                'authMethods' => [
                    [
                        'class' => SessionAuth::class
                    ]
                ]

            ]
        ];
    }
}
