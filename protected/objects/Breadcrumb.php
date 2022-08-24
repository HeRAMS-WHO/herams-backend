<?php

declare(strict_types=1);

namespace prime\objects;

use prime\interfaces\BreadcrumbInterface;
use prime\traits\BreadcrumbTrait;

class Breadcrumb implements BreadcrumbInterface
{
    public function __construct(
        private string $label,
        private array|string|null $url = null
    ) {

    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrl(): string|null|array
    {
        return $this->url;
    }
}
