<?php

declare(strict_types=1);

namespace prime\repositories;

use herams\api\models\NewProject;
use herams\api\models\UpdateProject;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\project\ProjectForBreadcrumbInterface;
use prime\interfaces\project\ProjectLocalesRetriever;
use prime\interfaces\RetrieveReadModelRepositoryInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\read\Project as ProjectRead;
use prime\models\forms\project\Create;
use prime\models\project\ProjectForBreadcrumb;
use prime\models\project\ProjectForExternalDashboard;
use prime\models\project\ProjectLocales;
use prime\traits\RepositorySave;
use prime\values\IntegerId;
use prime\values\ProjectId;
use prime\values\SurveyId;
use yii\web\NotFoundHttpException;

class ProjectRepository implements RetrieveReadModelRepositoryInterface, ProjectLocalesRetriever
{
    use RepositorySave;
    public function __construct(
        private readonly AccessCheckInterface $accessCheck,
        private readonly ActiveRecordHydratorInterface $activeRecordHydrator,
        private readonly ModelHydrator $hydrator
    ) {
    }

    public function create(NewProject|Create $model): ProjectId
    {
        $record = new Project();
        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new ProjectId($record->id);
    }

    public function retrieveForBreadcrumb(ProjectId $id): ProjectForBreadcrumbInterface
    {
        $record = Project::findOne([
            'id' => $id,
        ]);
        return new ProjectForBreadcrumb($record);
    }

    public function retrieveForRead(IntegerId $id): ProjectRead
    {
        $record = ProjectRead::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return $record;
    }

    public function retrieveForExport(ProjectId $id): Project
    {
        $record = Project::findOne([
            'id' => $id,
        ]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_EXPORT);
        return $record;
    }

    public function retrieveForUpdate(ProjectId $id): UpdateProject
    {
        $record = Project::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        $update = new UpdateProject($id);
        $this->activeRecordHydrator->hydrateRequestModel($record, $update);
        return $update;
    }

    public function save(UpdateProject $model): ProjectId
    {
        $record = Project::findOne([
            'id' => $model->id,
        ]);
        $this->internalSave($record, $model);
        return new ProjectId($record->id);
    }

    public function retrieveForExternalDashboard(ProjectId $id): ProjectForExternalDashboard
    {
        $record = ProjectRead::findOne([
            'id' => $id,
        ]);
        if (! isset($record) || null === $dashboard = $record->getOverride('dashboard')) {
            throw new NotFoundHttpException();
        };

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);

        return new ProjectForExternalDashboard($record->title, $dashboard);
    }

    public function retrieveAdminSurveyId(ProjectId $projectId): SurveyId
    {
        /** @var null|Project $project */
        $project = Project::find()->andWhere([
            'id' => $projectId->getValue(),
        ])->one();
        if (! isset($project)) {
            throw new NotFoundHttpException();
        }
        return $project->getAdminSurveyId();
    }

    public function retrieveDataSurveyId(ProjectId $projectId): SurveyId
    {
        /** @var null|Project $project */
        $project = Project::find()->andWhere([
            'id' => $projectId->getValue(),
        ])->one();
        if (! isset($project)) {
            throw new NotFoundHttpException();
        }
        return $project->getDataSurveyId();
    }

    public function retrieveProjectLocales(ProjectId $id): ProjectLocales
    {
        $project = Project::findOne([
            'id' => $id,
        ]);
        if (! isset($project)) {
            throw new NotFoundHttpException();
        }

        $this->accessCheck->requirePermission($project, Permission::PERMISSION_READ);

        var_dump($project->languages);
        die();
    }
}
