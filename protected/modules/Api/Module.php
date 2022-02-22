<?php

declare(strict_types=1);

namespace prime\modules\Api;

use yii\filters\ContentNegotiator;
use yii\web\GroupUrlRule;
use yii\web\Response;
use yii\web\UrlRule;

class Module extends \yii\base\Module
{
    public static function urlRule(): array
    {
        return [
            'class' => GroupUrlRule::class,
            'prefix' => 'api/',
            'rules' => [
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller:\w+>s',
                    'verb' => 'get',
                    'route' => '<controller>/index'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view',
                    'verb' => 'get'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>/<action:\w+>/<target_id:\d+>',
                    'route' => '<controller>/<action>',
                    'verb' => ['put', 'delete']
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>/<action:\w+>',
                    'route' => '<controller>/<action>',
                    'verb' => ['get', 'post']
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
                    'pattern' => '<p:.*>',
                    'route' => '',
                ]
            ]
        ];
    }

    public function behaviors()
    {
        return [
            ContentNegotiator::class => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }
}
