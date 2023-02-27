<?php

declare(strict_types=1);

namespace herams\common\domain\workspace;

use herams\api\domain\workspace\NewWorkspace;
use herams\api\domain\workspace\UpdateWorkspace;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\ModelHydratorInterface;
use herams\common\models\Permission;
use herams\common\models\Project;
use herams\common\models\Workspace;
use herams\common\queries\WorkspaceQuery;
use herams\common\values\IntegerId;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use prime\interfaces\WorkspaceForTabMenu;
use yii\web\NotFoundHttpException;

class WorkspaceRepository {
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ActiveRecordHydratorInterface $activeRecordHydrator,
        private ModelHydratorInterface $modelHydrator
    ) {
    }


    private function workspaceQuery(ProjectId $projectId): WorkspaceQuery
    {
        return Workspace::find()
            ->withFields('leadNames', 'latestUpdate', 'responseCount', 'facilityCount', 'favorite_id')
            ->andWhere([
                'project_id' => $projectId->getValue(),
            ]);
    }

    /**
     * @return list<Workspace>
     */
    public function retrieveForProject(ProjectId $id): array
    {
        $project = Project::findOne([
            'id' => $id->getValue(),
        ]);
        $this->accessCheck->requirePermission($project, Permission::PERMISSION_LIST_WORKSPACES);
        return $this->workspaceQuery($id)->all();
    }

    public function create(NewWorkspace $model): WorkspaceId
    {
        $record = new Workspace();
        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new WorkspaceId($record->id);
    }

    public function retrieveForRead(IntegerId|WorkspaceId $id): Workspace
    {
        $record = Workspace::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }
    public function retrieveForTabMenu(WorkspaceId $id): WorkspaceForTabMenu
    {
        $record = Workspace::find()
            ->withFields('facilityCount')
            ->andWhere([
                'id' => $id,
            ])->one();

        if (! isset($record)) {
            throw new NotFoundHttpException();
        }
        return new \prime\models\workspace\WorkspaceForTabMenu($this->accessCheck, $record);
    }

    public function retrieveForUpdate(WorkspaceId $workspaceId): UpdateWorkspace
    {
        $record = Workspace::findOne([
            'id' => $workspaceId->getValue(),
        ]);
        if (!isset($record)) {
            throw new NotFoundHttpException();
        }
        $model = new UpdateWorkspace($workspaceId);
        $this->activeRecordHydrator->hydrateRequestModel($record, $model);
        return $model;
    }
    public function update(UpdateWorkspace $model): void
    {
        $record = Workspace::findOne([
            'id' => $model->id,
        ]);
        \Yii::debug($model->attributes);

        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
    }

    public function getProjectId(WorkspaceId $id): ProjectId
    {
        $workspace = Workspace::findOne([
            'id' => $id,
        ]);
        if (! isset($workspace)) {
            throw new NotFoundHttpException();
        }
        $this->accessCheck->requirePermission($workspace, Permission::PERMISSION_READ);

        return $workspace->getProjectId();
    }
}
