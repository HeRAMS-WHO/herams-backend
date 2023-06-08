<?php

declare(strict_types=1);

namespace herams\common\components;

use herams\common\interfaces\CreateUrlInterface;
use yii\web\UrlRuleInterface;

/**
 * Rule to allow creation of frontend URLs from the backend and vice-versa
 */
final class RewriteRule implements UrlRuleInterface
{
    public function __construct(
        private CreateUrlInterface $apiUrlFactory,
        private CreateUrlInterface $frontendUrlFactory,
    ) {
    }

    public function parseRequest($manager, $request): bool
    {
        return false;
    }

    public function createUrl($manager, $route, $params): string|false
    {
        //        if (in_array(explode('/', $route, 2)[0], ['frontend', 'api'])) {
        //            var_dump($this->frontendUrlFactory->createUrl(explode('/', $route, 2)[1], $params));
        //            var_dump($route, $params); die();
        //        }
        $parts = explode('/', $route, 2);
        return match ($parts[0]) {
            'frontend' => $this->frontendUrlFactory->createUrl($parts[1] ?? '', $params),
            'api' => $this->apiUrlFactory->createUrl($parts[1] ?? '', $params),
            default => false
        };
    }
}
