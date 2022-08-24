<?php

declare(strict_types=1);

namespace prime\interfaces;

interface BreadcrumbInterface
{
    public function getLabel(): string;

    public function getUrl(): string|null|array;
}
