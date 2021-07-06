<?php
declare(strict_types=1);

namespace prime\traits;

trait BreadcrumbTrait
{
    private bool $encode = true;
    private array $htmlOptions = [];
    private string|null $label = null;
    private string|null $template = null;
    private string|array|null $url = null;

    public function getEncode(): bool
    {
        return $this->encode;
    }

    public function getHtmlOptions(): array
    {
        return $this->htmlOptions;
    }

    public function getLabel(): string|null
    {
        return $this->label;
    }

    public function getTemplate(): string|null
    {
        return $this->template;
    }

    public function getUrl(): string|array|null
    {
        return $this->url;
    }
}
