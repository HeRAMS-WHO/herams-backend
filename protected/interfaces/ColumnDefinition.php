<?php

declare(strict_types=1);

namespace prime\interfaces;

interface ColumnDefinition
{
    public function getHeaderCode(): string;
    public function getHeaderText(): string;

    public function getValue(HeramsResponseInterface $response): ?string;
}
