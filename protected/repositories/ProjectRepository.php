<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\project\ProjectForBreadcrumbInterface;
use prime\interfaces\RetrieveReadModelRepositoryInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\read\Project as ProjectRead;
use prime\models\forms\project\Create;
use prime\models\forms\project\Update as ProjectUpdate;
use prime\models\project\ProjectForExternalDashboard;
use prime\values\IntegerId;
use prime\models\project\ProjectForBreadcrumb;
use prime\values\ProjectId;
use yii\web\NotFoundHttpException;

class ProjectRepository implements RetrieveReadModelRepositoryInterface
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator
    ) {
    }

    public function create(Create $model): ProjectId
    {
        $record = new Project();
        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new ProjectId($record->id);
    }

    public function retrieveForBreadcrumb(ProjectId $id): ProjectForBreadcrumbInterface
    {
        $record = Project::findOne(['id' => $id]);
        return new ProjectForBreadcrumb($record);
    }

    public function retrieveForRead(IntegerId $id): ProjectRead
    {
        $record = ProjectRead::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }

    public function retrieveForUpdate(ProjectId $id): ProjectUpdate
    {
        $record = Project::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        $update = new ProjectUpdate(new ProjectId($record->id));
        $this->hydrator->hydrateFromActiveRecord($update, $record);

        return $update;
    }

    public function save(ProjectUpdate $model): ProjectId
    {
        $record = Project::findOne(['id' => $model->id]);
        $this->hydrator->hydrateActiveRecord($record, $model);
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
        $this->hydrator->hydrateFromActiveRecord($update, $record);

        return $update;
    }

    public function retrieveForRead(IntegerId $id): ProjectRead
    {
        $record = ProjectRead::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }

    public function retrieveForExternalDashboard(ProjectId $id): ProjectForExternalDashboard
    {
        $record = ProjectRead::findOne(['id' => $id]);
        if (!isset($record) || null === $dashboard = $record->getOverride('dashboard')) {
            throw new NotFoundHttpException();
        };

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);


        return new ProjectForExternalDashboard($record->title, $dashboard);
    }
}
