<?php

declare(strict_types=1);

namespace prime\interfaces;

/**
 * An asset bundle that is loaded as a javascript module.
 */
interface ModuleBundle
{
    /**
     * @return string Must be a javascript quoted string
     */
    public function getUrlForImport(): string;
}
