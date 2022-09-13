<?php

declare(strict_types=1);

namespace prime\helpers;

use prime\interfaces\AccessCheckInterface;

/**
 * Job handlers run unrestricted
 */
class JobAccessCheck implements AccessCheckInterface
{
    public function __construct()
    {
    }

    public function requirePermission(?object $model, string $permission, ?string $forbiddenMessage = null): void
    {
    }

    public function requireGlobalPermission(string $permission, ?string $forbiddenMessage = null): void
    {
    }

    public function checkPermission(object $model, string $permission): bool
    {
        return true;
    }
}
