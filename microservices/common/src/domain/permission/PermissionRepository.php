<?php

declare(strict_types=1);

namespace herams\common\domain\permission;

use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\Permission;
use herams\common\values\PermissionId;
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

    public function retrieveId(Authorizable $source, Authorizable $target, string $permission): PermissionId
    {
        $permission = Permission::find()->andWhere([
            'target' => $target->getAuthName(),
            'target_id' => $target->getId(),
            'source' => $source->getAuthName(),
            'source_id' => $source->getId(),
            'permission' => $permission
        ])->select('id')->one();
        return new PermissionId($permission->id);

    }
}
