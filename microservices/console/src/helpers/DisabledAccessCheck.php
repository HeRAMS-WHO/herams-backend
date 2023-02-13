<?php

declare(strict_types=1);

namespace herams\console\helpers;

use herams\common\interfaces\AccessCheckInterface;

/**
 * Access check that always allows an action, used in console
 */
final class DisabledAccessCheck implements AccessCheckInterface
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
