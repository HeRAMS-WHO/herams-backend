<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\CreateModelRepositoryInterface;
use prime\interfaces\RetrieveReadModelRepositoryInterface;
use prime\interfaces\RetrieveWorkspaceForNewFacility;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\Workspace as WorkspaceForm;
use prime\models\workspace\WorkspaceForNewFacility;
use prime\values\IntegerId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class WorkspaceRepository implements
    CreateModelRepositoryInterface,
    RetrieveReadModelRepositoryInterface,
    RetrieveWorkspaceForNewFacility
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator
    ) {
    }

    public function retrieveForRead(IntegerId|WorkspaceId $id): Workspace
    {
        $record = Workspace::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }

    public function create(Model|WorkspaceForm $model): WorkspaceId
    {
        requireParameter($model, WorkspaceForm::class, 'model');
        $record = new Workspace();
        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new WorkspaceId($record->id);
    }

    public function createFormModel(IntegerId $id): WorkspaceForm
    {
        $model = new WorkspaceForm(new ProjectId($id->getValue()));
        $this->accessCheck->requirePermission($model, Permission::PERMISSION_CREATE);
        return $model;
    }


    public function retrieveForNewFacility(WorkspaceId $id): WorkspaceForNewFacility
    {
        $workspace = Workspace::find()->with('project')->andWhere(['id' => $id])->one();
        if ($workspace === null) {
            throw new NotFoundHttpException();
        }
        return new WorkspaceForNewFacility($id, $workspace->title, new ProjectId($workspace->project->id), $workspace->project->title);

    }

}
