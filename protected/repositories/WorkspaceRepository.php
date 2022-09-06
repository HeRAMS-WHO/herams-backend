<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\ModelHydratorInterface;
use prime\interfaces\RetrieveReadModelRepositoryInterface;
use prime\interfaces\RetrieveWorkspaceForNewFacility;
use prime\interfaces\workspace\WorkspaceForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\interfaces\WorkspaceForTabMenu;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\Workspace as WorkspaceForm;
use prime\models\forms\workspace\Create;
use prime\models\forms\workspace\CreateForLimesurvey;
use prime\models\forms\workspace\UpdateForLimesurvey as WorkspaceUpdateForLimesurvey;
use prime\models\workspace\WorkspaceForBreadcrumb;
use prime\models\workspace\WorkspaceForCreateOrUpdateFacility;
use prime\modules\Api\models\NewWorkspace;
use prime\modules\Api\models\UpdateWorkspace;
use prime\objects\enums\ProjectType;
use prime\values\IntegerId;
use prime\values\ProjectId;
use prime\values\SurveyId;
use prime\values\WorkspaceId;
use yii\base\InvalidArgumentException;
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

    public function create(CreateForLimesurvey|Create|NewWorkspace $model): WorkspaceId
    {
        if ($model instanceof CreateForLimesurvey) {
            $record = new WorkspaceForLimesurvey();
        } else {
            $record = new Workspace();
        }
        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new WorkspaceId($record->id);
    }

    public function retrieveForBreadcrumb(WorkspaceId $id): ForBreadcrumbInterface
    {
        $record = Workspace::findOne([
            'id' => $id,
        ]);
        return new WorkspaceForBreadcrumb($record);
    }

    public function retrieveForFacilityList(WorkspaceId $id): WorkspaceForCreateOrUpdateFacility
    {
        /** @var null|Workspace $workspace */
        $workspace = Workspace::find()->with('project')->andWhere([
            'id' => $id,
        ])->one();
        $this->accessCheck->requirePermission($workspace, Permission::PERMISSION_READ);
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

    public function createFormModel(IntegerId $id): WorkspaceForm
    {
        $model = new WorkspaceForm(new ProjectId($id->getValue()));
        $this->accessCheck->requirePermission($model, Permission::PERMISSION_CREATE);
        return $model;
    }

    public function retrieveForNewFacility(WorkspaceId $id): WorkspaceForCreateOrUpdateFacility
    {
        /** @var null|Workspace $workspace */
        $workspace = Workspace::find()->with('project')->andWhere([
            'id' => $id,
        ])->one();
        $this->accessCheck->requirePermission($workspace, Permission::PERMISSION_CREATE_FACILITY);
        $project = $workspace->project;

        if ($project->type->equals(ProjectType::limesurvey())) {
            throw new InvalidArgumentException('Cannot create facility for Limesurvey project this way.');
        }

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

    public function retrieveForUpdate(IntegerId|WorkspaceId $id): WorkspaceUpdateForLimesurvey|UpdateWorkspace
    {
        $record = Workspace::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $workspaceId = new WorkspaceId($record->id);

        if ($record instanceof WorkspaceForLimesurvey) {
            $update = new WorkspaceUpdateForLimesurvey($workspaceId);
        } else {
            $update = new UpdateWorkspace($workspaceId);
        }
        $this->activeRecordHydrator->hydrateRequestModel($record, $update);

        return $update;
    }

    public function save(UpdateWorkspace|WorkspaceUpdateForLimesurvey $model): WorkspaceId
    {
        $record = Workspace::findOne([
            'id' => $model->getId(),
        ]);
        \Yii::debug($model->attributes);

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
