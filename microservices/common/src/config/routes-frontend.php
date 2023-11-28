<?php

declare(strict_types=1);

use herams\common\components\RewriteRule;

$reactRoutes = json_decode(file_get_contents(
    __DIR__.'/react/react-routes.json'
), true);
function generateRoutes($reactRoutes)
{
    $routes = [];
    foreach($reactRoutes as $key => $reactRoute) {
        $routeInYii2Format = '';
        $partsRoute = explode('/', $key);
        unset($partsRoute[0]);
        foreach($partsRoute as $partRoute) {
            if (strpos($partRoute, ':') === 0) {
                $routeInYii2Format .= '/<'.substr($partRoute, 1).':[\w-]+>';
            } else {
                $routeInYii2Format .= '/' . $partRoute;
            }
        }
        if (!($reactRoute['component'] ?? false)){
            continue;
        }
        //Check if last character is a slash
        if (substr($routeInYii2Format, -1) === '/') {
            $routeInYii2Format = substr($routeInYii2Format, 0, -1);
        }
        $routes[] = [
            'pattern' => $routeInYii2Format,
            'route' => 'react/index'
        ];
        $routes[] = [
            'pattern' => $routeInYii2Format . '/',
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
