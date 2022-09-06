<?php

declare(strict_types=1);

namespace prime\interfaces;

interface ValidationErrorCollection
{
    public function addError(string $attribute, string $errorDescription): void;

    /**
     * Remove all errors
     */
    public function clearErrors(): void;

    /**
     * @return list<string>
     */
    public function getErrors(): array;
}
