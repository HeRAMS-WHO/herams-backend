<?php
declare(strict_types=1);

namespace prime\objects;

class Breadcrumb
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

    public function setEncode(bool $value): self
    {
        $this->encode = $value;
        return $this;
    }

    public function setHtmlOptions(array $value): self
    {
        $this->htmlOptions = $value;
        return $this;
    }

    public function setLabel(string|null $value): self
    {
        $this->label = $value;
        return $this;
    }

    public function setTemplate(string|null $value): self
    {
        $this->template = $value;
        return $this;
    }

    public function setUrl(string|array|null $value): self
    {
        $this->url = $value;
        return $this;
    }
}
