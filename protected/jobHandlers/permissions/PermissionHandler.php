<?php
declare(strict_types=1);

namespace prime\jobHandlers\permissions;

use JCIT\jobqueue\exceptions\PermanentException;
use JCIT\jobqueue\interfaces\JobHandlerInterface;
use prime\models\ar\Permission;

abstract class PermissionHandler implements JobHandlerInterface
{
    protected function getPermission(int $accessRequestId): ?Permission
    {
        return Permission::findOne(['id' => $accessRequestId]);
    }

    protected function getPermissionOrThrow(int $accessRequestId): Permission
    {
        $result = $this->getPermission($accessRequestId);

        if (!$result) {
            throw new PermanentException('No such Permission.');
        }

        return $result;
    }
}
