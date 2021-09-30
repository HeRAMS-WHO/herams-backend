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
use prime\models\workspace\WorkspaceForBreadcrumb;
use prime\models\workspace\WorkspaceForNewOrUpdateFacility;
use prime\objects\LanguageSet;
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
        $record = WorkspaceForLimesurvey::findOne(['id' => $id]);
        return new WorkspaceForBreadcrumb($record);
    }

    public function retrieveForFacilityList(WorkspaceId $id): WorkspaceForNewOrUpdateFacility
    {
        /** @var null|WorkspaceForLimesurvey $workspace */
        $workspace = WorkspaceForLimesurvey::find()->with('project')->andWhere(['id' => $id])->one();
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
        /** @var null|WorkspaceForLimesurvey $workspace */
        $workspace = WorkspaceForLimesurvey::find()->with('project')->andWhere(['id' => $id])->one();
        $this->accessCheck->requirePermission($workspace, Permission::PERMISSION_READ);
        $project = $workspace->project;

        return new WorkspaceForNewOrUpdateFacility($id, $workspace->title, new ProjectId($project->id), $project->title, $project->getLanguageSet());
    }

    public function retrieveForRead(IntegerId|WorkspaceId $id): WorkspaceForLimesurvey
    {
        $record = WorkspaceForLimesurvey::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }

    public function retrieveForShare(WorkspaceId $id): WorkspaceForLimesurvey
    {
        $record = WorkspaceForLimesurvey::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_SHARE);

        return $record;
    }

    public function retrieveForTabMenu(WorkspaceId $id): WorkspaceForTabMenu
    {
        $record = WorkspaceForLimesurvey::find()
            ->withFields('facilityCount')
            ->andWhere(['id' => $id])->one();

        if (!isset($record)) {
            throw new NotFoundHttpException();
        }
        return new \prime\models\workspace\WorkspaceForTabMenu($this->accessCheck, $record);
    }

    public function retrieveForWrite(IntegerId|WorkspaceId $id): WorkspaceForLimesurvey
    {
        $record = WorkspaceForLimesurvey::findOne(['id' => $id]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        return $record;
    }
}
