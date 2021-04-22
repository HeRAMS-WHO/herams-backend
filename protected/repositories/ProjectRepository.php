<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\read\Project as ProjectRead;
use prime\models\forms\project\Create;
use prime\models\forms\project\Update as ProjectUpdate;
use prime\values\ProjectId;

class ProjectRepository
{
    public function __construct(private AccessCheckInterface $accessCheck)
    {
    }


    public function create(Create $model): ProjectId
    {
        $record = new Project();
        $hydrator = new ModelHydrator();
        $hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new ProjectId($record->id);
    }

    public function save(ProjectUpdate $model): ProjectId
    {
        $record = Project::findOne(['id' => $model->id]);
        $hydrator = new ModelHydrator();
        $hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new ProjectId($record->id);
    }

    public function retrieveForUpdate(ProjectId $id): ProjectUpdate
    {
        $record = Project::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        $update = new ProjectUpdate(new ProjectId($record->id));
        $hydrator = new ModelHydrator();
        $hydrator->hydrateFromActiveRecord($update, $record);

        return $update;
    }

    public function retrieveForRead(ProjectId $id): ProjectRead
    {
        $record = ProjectRead::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }
}
