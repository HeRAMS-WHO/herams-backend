<?php

declare(strict_types=1);

namespace prime\components;

use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

class ApiRewriteRule implements UrlRuleInterface
{
    public function __construct(
        private UrlManager $apiUrlManager
    ) {
    }

    public function parseRequest($manager, $request): bool
    {
        return false;
    }

    public function createUrl($manager, $route, $params)
    {
        if (str_starts_with($route, 'api/')) {
            // Strip prefix
            $route = substr($route, 4);

            $apiUrl = $this->apiUrlManager->createUrl([$route, ...$params]);
            if ($apiUrl !== false) {
                return $apiUrl;
                return $manager->createUrl(['api-proxy/core']) . $apiUrl;
            } else {
                die($apiUrl);
            }
        }
        if (str_contains($route, 'api') && ! str_contains($route, 'api-proxy')) {
            var_dump($route);
            die();
        }
        return false;
    }
}
