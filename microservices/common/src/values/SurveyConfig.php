<?php

declare(strict_types=1);

namespace herams\common\values;

use PhpParser\Error;

class SurveyConfig extends JsonField
{
    public function __construct(object|array|string $value)
    {
        parent::__construct($value);
        if (!isset($this->getValue()->page) || ! is_array($this->getValue()->page)){
            throw new Error('It is invalid because page property was expected');
        }
    }
}
