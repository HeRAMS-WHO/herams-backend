<?php

declare(strict_types=1);

namespace prime\jobs\permissions;

use JCIT\jobqueue\interfaces\JobInterface;

abstract class PermissionJob implements JobInterface
{
    public function __construct(
        private int $permissionsId
    ) {
    }

    public static function fromArray(array $config): JobInterface
    {
        $class = static::class;
        return new $class($config['permissionId']);
    }

    public function getPermissionId(): int
    {
        return $this->permissionsId;
    }

    public function jsonSerialize(): array
    {
        return [
            'permissionId' => $this->permissionsId,
        ];
    }
}
