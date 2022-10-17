<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\ModelHydratorInterface;
use prime\interfaces\RetrieveReadModelRepositoryInterface;
use prime\interfaces\RetrieveWorkspaceForNewFacility;
use prime\interfaces\WorkspaceForTabMenu;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\models\workspace\WorkspaceForCreateOrUpdateFacility;
use prime\modules\Api\models\NewWorkspace;
use prime\modules\Api\models\UpdateWorkspace;
use prime\queries\WorkspaceQuery;
use prime\values\IntegerId;
use prime\values\ProjectId;
use prime\values\SurveyId;
use prime\values\UserId;
use prime\values\WorkspaceId;
use yii\web\NotFoundHttpException;

class WorkspaceRepository implements
    RetrieveReadModelRepositoryInterface,
    RetrieveWorkspaceForNewFacility
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ActiveRecordHydratorInterface $activeRecordHydrator,
        private ModelHydratorInterface $modelHydrator
    ) {
    }


    public function retrieveFavoritesForProject(ProjectId $id, UserId $userId): array
    {
        $project = Project::findOne([
            'id' => $id->getValue(),
        ]);
        $this->accessCheck->requirePermission($project, Permission::PERMISSION_LIST_WORKSPACES);

        return $this->workspaceQuery($id)
            ->isFavoriteOfUser($userId)
            ->all();
    }

    private function workspaceQuery(ProjectId $projectId): WorkspaceQuery
    {
        return Workspace::find()
            ->withFields('leadNames', 'latestUpdate', 'responseCount', 'facilityCount', 'isFavorite')
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

    public function retrieveForNewFacility(WorkspaceId $id): WorkspaceForCreateOrUpdateFacility
    {
        /** @var null|Workspace $workspace */
        $workspace = Workspace::find()->with('project')->andWhere([
            'id' => $id,
        ])->one();
        $this->accessCheck->requirePermission($workspace, Permission::PERMISSION_CREATE_FACILITY);
        $project = $workspace->project;

        return new WorkspaceForCreateOrUpdateFacility(
            new SurveyId($project->admin_survey_id),
            $id,
            $project->getLanguageSet(),
            new ProjectId($project->id),
            $project->title,
            $workspace->title,
        );
    }

    public function retrieveForRead(IntegerId|WorkspaceId $id): Workspace
    {
        $record = Workspace::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }

    public function retrieveForRequestAccess(WorkspaceId $id): Workspace
    {
        $record = Workspace::find()->andWhere([
            'id' => $id,
        ])->asArray()->one();

        $workspace = Workspace::instantiate([]);
        Workspace::populateRecord($workspace, $record);
        return $workspace;
    }

    public function retrieveForShare(WorkspaceId $id): Workspace
    {
        $record = Workspace::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_SHARE);

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

    public function retrieveForUpdate(IntegerId|WorkspaceId $id): UpdateWorkspace
    {
        $record = Workspace::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $workspaceId = new WorkspaceId($record->id);

        $update = new UpdateWorkspace($workspaceId);
        $this->activeRecordHydrator->hydrateRequestModel($record, $update);

        return $update;
    }

    public function save(UpdateWorkspace $model): WorkspaceId
    {
        $record = Workspace::findOne([
            'id' => $model->getId(),
        ]);

        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new WorkspaceId($record->id);
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
