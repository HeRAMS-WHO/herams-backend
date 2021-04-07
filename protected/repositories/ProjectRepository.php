<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\read\Project as ProjectRead;
use prime\models\forms\project\Create;
use prime\models\forms\project\Update;
use prime\values\ProjectId;

class ProjectRepository
{
    public function __construct(private AccessCheckInterface $accessCheck)
    {
    }


    public function create(Create $model): ProjectId
    {
        $record = new Project();
        $record->setAttributes($model->attributes);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new ProjectId($record->id);
    }

    public function save(Update $model): ProjectId
    {
        $record = Project::findOne(['id' => $model->id]);
        $record->setAttributes($model->attributes, false);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new ProjectId($record->id);
    }

    public function retrieveForUpdate(ProjectId $id): Update
    {
        $record = Project::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        $update = new Update(new ProjectId($record->id));
        $update->ensureBehaviors();
        if (!$update->isAttributeSafe('i18nTitle')) {
            throw new \Exception('bad');
        }
        $update->setAttributes($record->attributes, false);

        return $update;
    }

    public function retrieveForRead(ProjectId $id): ProjectRead
    {
        $record = ProjectRead::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        return $record;
    }
}
