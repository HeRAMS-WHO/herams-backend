<?php

declare(strict_types=1);

use herams\common\components\RewriteRule;

return [
    [
        'pattern' => '/api-proxy/<api:[\w-]+>/<sub:.*>',
        'route' => '/api-proxy/<api>',
    ],
    [
        'class' => RewriteRule::class,
    ],
    [
        'pattern' => '<controller>',
        'route' => '<controller>',
    ],
    [
        'pattern' => '<controller>/<id:\d+>',
        'route' => '<controller>/view',
    ],
    [
        'pattern' => '<controller>/<id:[\w-]+>/<action:[\w-]+>',
        'route' => '<controller>/<action>',
    ],
    [
        'pattern' => '<controller>/<pid:[\w-]+>/<action:[\w-]+>/<cid:[\w-]+>',
        'route' => '<controller>/<action>',
    ],
    [
        'pattern' => '<controller>/<action:[\w-]+>',
        'route' => '<controller>/<action>',
    ],
    // For testing.
    [
        'pattern' => '/',
        'route' => 'site/world-map',
    ],


];
