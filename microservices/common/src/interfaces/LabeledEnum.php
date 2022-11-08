<?php

declare(strict_types=1);

namespace herams\common\interfaces;

interface LabeledEnum
{
    public function label(): string;
}
