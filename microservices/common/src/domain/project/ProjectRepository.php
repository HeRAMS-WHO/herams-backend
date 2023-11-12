<?php

declare(strict_types=1);

namespace herams\common\domain\project;

use herams\api\domain\project\NewProject;
use herams\api\domain\project\UpdateProject;
use herams\common\domain\accessRequest\AccessRequestRepository;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\favorite\FavoriteRepository;
use herams\common\domain\page\PageRepository;
use herams\common\domain\permission\PermissionRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\enums\UserPermissions;
use herams\common\helpers\Locale;
use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\PermissionOld;
use herams\common\models\Project;
use herams\common\traits\RepositorySave;
use herams\common\values\IntegerId;
use herams\common\values\ProjectId;
use herams\common\values\SurveyId;
use herams\common\values\WorkspaceId;
use prime\interfaces\project\ProjectForBreadcrumbInterface;
use prime\models\ar\read\Project as ProjectRead;
use prime\models\project\ProjectForBreadcrumb;
use prime\models\project\ProjectForExternalDashboard;
use prime\models\project\ProjectLocales;
use yii\web\NotFoundHttpException;

class ProjectRepository implements ProjectLocalesRetriever
{
    use RepositorySave;

    public function __construct(
        private readonly AccessCheckInterface $accessCheck,
        private readonly ActiveRecordHydratorInterface $activeRecordHydrator,
        private readonly ModelHydrator $hydrator
    ) {
    }

    public function retrieveById(ProjectId $projectId): Project
    {
        return Project::findOne([
            'id' => $projectId->getValue(),
        ]);
    }

    public function create(NewProject $model): ProjectId
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

    /**
     * @return array $roles
     */
    public function retrieveRoles(ProjectId $id): array
    {
        $record = Project::findOne([
            'id' => $id,
        ]);
        return $record->roles;
    }

    public function retrieveForRead(IntegerId $id): ProjectRead
    {
        $record = ProjectRead::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, PermissionOld::PERMISSION_READ);

        return $record;
    }

    public function retrieveForExport(ProjectId $id): Project
    {
        $record = Project::findOne([
            'id' => $id,
        ]);
        $this->accessCheck->requirePermission($record, PermissionOld::PERMISSION_EXPORT);
        return $record;
    }

    public function getProject(ProjectId $id): Project
    {
        return Project::findOne([
            'id' => $id,
        ]);
    }

    public function retrieveForUpdate(ProjectId $id): UpdateProject
    {
        $record = Project::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, PermissionOld::PERMISSION_WRITE);

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

    public function emptyProject(
        ProjectId $projectId,
        WorkspaceRepository $workspaceRepository,
        FacilityRepository $facilityRepository,
        SurveyResponseRepository $surveyResponseRepository,
        AccessRequestRepository $accessRequestRepository,
        FavoriteRepository $favoriteRepository,
        PermissionRepository $permissionRepository,
        PageRepository $pageRepository
    ) {
        $workspaces = $workspaceRepository->retrieveAllWorkspacesByProjectId($projectId);
        foreach (($workspaces ?? []) as $workspace) {
            $workspaceId = new WorkspaceId($workspace->id);
            $accessRequestRepository->deleteAll([
                'target_class' => UserPermissions::CAN_ACCESS_TO_WORKSPACE->value,
                'target_id' => $workspace->id,
            ]);
            $favoriteRepository->deleteAll([
                'target_class' => UserPermissions::CAN_ACCESS_TO_WORKSPACE->value,
                'target_id' => $workspace->id,
            ]);

            $permissionRepository->deleteAll([
                'target' => UserPermissions::CAN_ACCESS_TO_WORKSPACE->value,
                'target_id' => $workspace->id,
            ]);
            $facilities = $facilityRepository->retrieveAllByWorkspaceId($workspaceId);
            foreach (($facilities ?? []) as $facility) {
                $surveyResponseRepository->deleteAll([
                    'facility_id' => $facility->id,
                ]);
            }
            $facilityRepository->deleteAll([
                'workspace_id' => $workspace->id,
            ]);
        }
        $pageRepository->deleteAll([
            'project_id' => $projectId->getValue(),
        ]);
        $workspaceRepository->deleteAll([
            'project_id' => $projectId->getValue(),
        ]);
    }

    public function deleteAll(array $condition): void
    {
        Project::deleteAll($condition);
    }

    public function retrieveForExternalDashboard(ProjectId $id): ProjectForExternalDashboard
    {
        $record = ProjectRead::findOne([
            'id' => $id,
        ]);
        if (! isset($record) || null === $dashboard = $record->dashboard_url) {
            throw new NotFoundHttpException();
        };

        $this->accessCheck->requirePermission($record, PermissionOld::PERMISSION_READ);

        return new ProjectForExternalDashboard($record->getTitle(), $dashboard);
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

        $this->accessCheck->requirePermission($project, PermissionOld::PERMISSION_READ);
        return new ProjectLocales(...Locale::fromValues($project->languages ?? []));
    }
}
