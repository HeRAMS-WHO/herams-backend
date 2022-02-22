<?php

declare(strict_types=1);

namespace prime\traits;

use prime\interfaces\AccessCheckInterface;

trait CanCurrentUser
{
    private AccessCheckInterface $accessCheck;

    abstract private function getModel(): object;

    public function canCurrentUser(string $permission): bool
    {
        return $this->accessCheck->checkPermission($this->getModel(), $permission);
    }
}
