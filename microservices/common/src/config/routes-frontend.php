<?php

declare(strict_types=1);

use herams\common\components\RewriteRule;

function flattenJSON($json, &$result = []) {
    if ($json['migrated'] ?? false){
        $result[] = $json['URL'];
    }
    if (isset($json['children'])) {
        foreach($json['children'] as $child) {
            flattenJSON($child, $result);
        }
    }
    return $result;
}
$reactRoutes = flattenJSON(json_decode(file_get_contents(
    __DIR__.'/react/react-routes.json'
), true));
function generateRoutes($reactRoutes)
{
    $routes = [];
    foreach($reactRoutes as $reactRoute) {
        $routes[] = [
            'pattern' => $reactRoute,
            'route' => 'react/index'
        ];
    }
    return $routes;
}
return [
    ...generateRoutes($reactRoutes),
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
