<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\models\ar\Permission;
use yii\base\InvalidArgumentException;

class PermissionRepository
{
    public function retrieve(int $id): ?Permission
    {
        return Permission::findOne([
            'id' => $id,
        ]);
    }

    public function retrieveOrThrow(int $id): ?Permission
    {
        $result = $this->retrieve($id);

        if (! $result) {
            throw new InvalidArgumentException('No such Permission.');
        }

        return $result;
    }
}
