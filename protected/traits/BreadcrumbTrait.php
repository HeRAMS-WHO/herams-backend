<?php

declare(strict_types=1);

namespace prime\traits;

/**
 * Trait BreadcrumbTrait
 * @package prime\traits
 *
 * @codeCoverageIgnore it are only properties and getters
 */
trait BreadcrumbTrait
{
    private string|null $label = null;

    private string|null $template = null;

    private string|array|null $url = null;

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrl(): string|null|array
    {
        return $this->url;
    }
}
