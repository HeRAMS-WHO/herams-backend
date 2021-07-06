<?php
declare(strict_types=1);

namespace prime\objects;

use prime\interfaces\BreadcrumbInterface;
use prime\traits\BreadcrumbTrait;

class Breadcrumb implements BreadcrumbInterface
{
    use BreadcrumbTrait;

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
