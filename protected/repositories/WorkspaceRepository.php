<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\CreateModelRepositoryInterface;
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
use prime\models\forms\workspace\Update as WorkspaceUpdate;
use prime\models\forms\workspace\UpdateForLimesurvey as WorkspaceUpdateForLimesurvey;
use prime\models\workspace\WorkspaceForBreadcrumb;
use prime\models\workspace\WorkspaceForNewOrUpdateFacility;
use prime\objects\enums\ProjectType;
use prime\objects\LanguageSet;
use prime\values\IntegerId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class WorkspaceRepository implements
    CreateModelRepositoryInterface,
    RetrieveReadModelRepositoryInterface,
    RetrieveWorkspaceForNewFacility
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator,
    ) {
    }

    public function create(Model|CreateForLimesurvey|Create $model): WorkspaceId
    {
        if ($model instanceof CreateForLimesurvey) {
            $record = new WorkspaceForLimesurvey();
        } else {
            $record = new Workspace();
        }
        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new WorkspaceId($record->id);
    }

    public function retrieveForBreadcrumb(WorkspaceId $id): ForBreadcrumbInterface
    {
        $record = Workspace::findOne(['id' => $id]);
        return new WorkspaceForBreadcrumb($record);
    }

    public function retrieveForFacilityList(WorkspaceId $id): WorkspaceForNewOrUpdateFacility
    {
        /** @var null|Workspace $workspace */
        $workspace = Workspace::find()->with('project')->andWhere(['id' => $id])->one();
        $this->accessCheck->requirePermission($workspace, Permission::PERMISSION_READ);
        $project = $workspace->project;

        return new WorkspaceForNewOrUpdateFacility($id, $workspace->title, new ProjectId($project->id), $project->title, LanguageSet::from($project->languages));
    }

    public function createFormModel(IntegerId $id): WorkspaceForm
    {
        $model = new WorkspaceForm(new ProjectId($id->getValue()));
        $this->accessCheck->requirePermission($model, Permission::PERMISSION_CREATE);
        return $model;
    }

    public function retrieveForNewFacility(WorkspaceId $id): WorkspaceForNewOrUpdateFacility
    {
        /** @var null|Workspace $workspace */
        $workspace = Workspace::find()->with('project')->andWhere(['id' => $id])->one();
        $this->accessCheck->requirePermission($workspace, Permission::PERMISSION_CREATE_FACILITY);
        $project = $workspace->project;

        if ($project->type->equals(ProjectType::limesurvey())) {
            throw new InvalidArgumentException('Cannot create facility for Limesurvey project this way.');
        }

        return new WorkspaceForNewOrUpdateFacility($id, $workspace->title, new ProjectId($project->id), $project->title, $project->getLanguageSet());
    }

    public function retrieveForRead(IntegerId|WorkspaceId $id): Workspace
    {
        $record = Workspace::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }

    public function retrieveForRequestAccess(WorkspaceId $id): Workspace
    {
        $record = Workspace::find()->andWhere(['id' => $id])->asArray()->one();

        $workspace = Workspace::instantiate([]);
        Workspace::populateRecord($workspace, $record);
        return $workspace;
    }

    public function retrieveForShare(WorkspaceId $id): Workspace
    {
        $record = Workspace::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_SHARE);

        return $record;
    }

    public function retrieveForTabMenu(WorkspaceId $id): WorkspaceForTabMenu
    {
        $record = Workspace::find()
            ->withFields('facilityCount')
            ->andWhere(['id' => $id])->one();

        if (!isset($record)) {
            throw new NotFoundHttpException();
        }
        return new \prime\models\workspace\WorkspaceForTabMenu($this->accessCheck, $record);
    }

    public function retrieveForUpdate(IntegerId|WorkspaceId $id): WorkspaceUpdate|WorkspaceUpdateForLimesurvey
    {
        $record = Workspace::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $workspaceId = new WorkspaceId($record->id);

        if ($record instanceof WorkspaceForLimesurvey) {
            $update = new WorkspaceUpdateForLimesurvey($workspaceId);
        } else {
            $update = new WorkspaceUpdate($workspaceId);
        }
        $this->hydrator->hydrateFromActiveRecord($update, $record);

        return $update;
    }

    public function save(WorkspaceUpdate|WorkspaceUpdateForLimesurvey $model): WorkspaceId
    {
        $record = Workspace::findOne(['id' => $model->getId()]);

        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new WorkspaceId($record->id);
    }
}
