<?php

declare(strict_types=1);

namespace prime\interfaces;

interface ModelWithPrepare
{
    public function prepare(): array;
}
