<?php

declare(strict_types=1);

namespace herams\common\interfaces;

interface ValidateTypeInterface
{
    public function validate(object $source, object $target): bool;
}
