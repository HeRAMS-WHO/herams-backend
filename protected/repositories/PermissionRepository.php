<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\models\ar\Permission;
use SamIT\abac\values\Authorizable;
use yii\base\InvalidArgumentException;

class PermissionRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ActiveRecordHydratorInterface $activeRecordHydrator,
        private ModelHydrator $hydrator
    ) {
    }

    public function retrieve(int $id): Permission
    {
        $result = Permission::findOne([
            'id' => $id,
        ]);

        if (! $result) {
            throw new InvalidArgumentException('No such Permission.');
        }

        return $result;
    }

    /**
     * @return list<Permission>
     */
    public function retrieveForTarget(Authorizable $target): array
    {
        $this->accessCheck->requirePermission($target, Permission::PERMISSION_SHARE);
        // Criteria are not secure.
        return Permission::find()->andWhere([
            'target' => $target->getAuthName(),
            'target_id' => $target->getId(),
        ])->all();
    }
}
