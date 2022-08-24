<?php
declare(strict_types=1);

namespace prime\interfaces;

interface ValidationErrorCollection
{
    /**
     * @param string $errorDescription
     * @return void
     */
    public function addError(string $attribute, string $errorDescription): void;

    /**
     * Remove all errors
     * @return void
     */
    public function clearErrors(): void;

    /**
     * @return list<string>
     */
    public function getErrors(): array;


}
