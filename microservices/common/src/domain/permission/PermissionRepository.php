<?php

declare(strict_types=1);

namespace herams\common\domain\permission;

use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\PermissionOld;
use herams\common\values\PermissionId;
use SamIT\abac\interfaces\Grant;
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

    public function deleteAll(array $condition): void
    {
        PermissionOld::deleteAll($condition);
    }

    public function retrieve(int $id): PermissionOld
    {
        $result = PermissionOld::findOne([
            'id' => $id,
        ]);

        if (! $result) {
            throw new InvalidArgumentException('No such PermissionOld.');
        }

        return $result;
    }

    /**
     * @return list<PermissionOld>
     */
    public function retrieveForTarget(Authorizable $target): array
    {
        $this->accessCheck->requirePermission($target, PermissionOld::PERMISSION_SHARE);
        // Criteria are not secure.
        return PermissionOld::find()->andWhere([
            'target' => $target->getAuthName(),
            'target_id' => $target->getId(),
        ])->all();
    }

    public function retrieveId(Grant $grant): PermissionId
    {
        $target = $grant->getTarget();
        $source = $grant->getSource();
        $permission = PermissionOld::find()->andWhere([
            'target' => $target->getAuthName(),
            'target_id' => $target->getId(),
            'source' => $source->getAuthName(),
            'source_id' => $source->getId(),
            'permission' => $grant->getPermission(),
        ])->select('id')->one();
        return new PermissionId($permission->id);
    }
}
