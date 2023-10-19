<?php

declare(strict_types=1);

namespace herams\common\values;

use herams\common\interfaces\JsonFieldInterface;
use PhpParser\Error;

class JsonField implements JsonFieldInterface
{
    private object|array $value;

    public function __construct(array|string|object $value)
    {
        try {
            if (is_string($value)) {
                $value = json_decode($value);
            }
        } catch (Error $error) {
            throw new Error('The provided value does not have a valid JSON format');
        }
        $this->value = $value;
    }

    public function getValue(): array|object
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return json_encode($this->value);
    }
}
