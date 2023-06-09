<?php

declare(strict_types=1);

namespace herams\common\helpers\surveyjs;

use Collecthor\DataInterfaces\ValueInterface;

class ArrayMapValue implements ValueInterface
{
    /**
     * @param array<string, string> $value
     */
    public function __construct(
        private array $value
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function getRawValue(): array
    {
        return $this->value;
    }
}
