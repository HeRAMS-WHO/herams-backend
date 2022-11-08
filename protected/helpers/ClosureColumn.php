<?php

declare(strict_types=1);

namespace prime\helpers;

use herams\common\interfaces\HeramsResponseInterface;
use prime\interfaces\ColumnDefinition;

class ClosureColumn implements ColumnDefinition
{
    private $value;

    private $headerCode;

    private $headerText;

    public function __construct(\Closure $value, string $headerCode, string $headerText)
    {
        $this->headerCode = $headerCode;
        $this->headerText = $headerText;
        $this->value = $value;
    }

    public function getHeaderCode(): string
    {
        return $this->headerCode;
    }

    public function getHeaderText(): string
    {
        return $this->headerText;
    }

    public function getValue(HeramsResponseInterface $response): ?string
    {
        return ($this->value)($response);
    }
}
