<?php

declare(strict_types=1);

namespace prime\interfaces;

interface ValidateTypeInterface
{
    public function validate(object $source, object $target): bool;
}
