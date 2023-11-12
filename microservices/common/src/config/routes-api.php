<?php

declare(strict_types=1);

use herams\common\components\RewriteRule;
use yii\web\UrlRule;

return [
    [
        'class' => RewriteRule::class,
    ],
    [
        'class' => UrlRule::class,
        'pattern' => '<controller:[\w-]+>',
        'route' => '<controller>/create',
        'verb' => 'POST',
    ],
    [
        'class' => UrlRule::class,
        'pattern' => 'user-role/project/<id:\d+>/index',
        'route' => 'user-role/index',
        'verb' => 'GET',
    ],
    [
        'class' => UrlRule::class,
        'pattern' => '<controller:[\w-]+>/<id:\d+>',
        'route' => '<controller>/delete',
        'verb' => 'DELETE',
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
        'pattern' => '<controller:\w+>/<id:\d+>',
        'route' => '<controller>/view',
        'verb' => 'get',
    ],
    [
        'class' => UrlRule::class,
        'pattern' => '<controller:\w+>/<id:\d+>',
        'route' => '<controller>/update',
        'verb' => ['post', 'put'],
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
];
