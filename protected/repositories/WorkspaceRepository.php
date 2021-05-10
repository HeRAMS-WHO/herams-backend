<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\values\WorkspaceId;

class WorkspaceRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator
    ) {
    }

    public function retrieveForRead(WorkspaceId $id): Workspace
    {
        $record = Workspace::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }
}
