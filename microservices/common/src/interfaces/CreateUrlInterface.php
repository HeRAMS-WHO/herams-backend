<?php

declare(strict_types=1);

namespace herams\common\interfaces;

/**
 * Models the URL creation part of Yii's UrlManager
 */
interface CreateUrlInterface
{
    public function createUrl(string $route, array $params): string;
}
