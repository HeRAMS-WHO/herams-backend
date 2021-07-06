<?php
declare(strict_types=1);

namespace prime\interfaces;

interface BreadcrumbInterface
{
    public function getEncode(): bool;
    public function getHtmlOptions(): array;
    public function getLabel(): string|null;
    public function getTemplate(): string|null;
    public function getUrl(): string|array|null;
}
