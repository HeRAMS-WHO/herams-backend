<?php

declare(strict_types=1);

namespace prime\components;

use prime\objects\Breadcrumb;
use prime\objects\BreadcrumbCollection;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\helpers\Url;

/**
 * This service defines the breadcrumb structure based on given ID.
 * @depends \Yii::$app->requestedRoute
 */
class BreadcrumbService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private WorkspaceRepository $workspaceRepository,
        private FacilityRepository $facilityRepository,
    ) {
    }

    public function retrieveForAdministration(): BreadcrumbCollection
    {
        return new BreadcrumbCollection();
    }

    public function retrieveForProjects(): BreadcrumbCollection
    {
        return $this->retrieveForAdministration()->add(new Breadcrumb(\Yii::t('app', 'Projects'), Url::to(['project/index'])));
    }

    public function retrieveForProject(ProjectId $id): BreadcrumbCollection
    {
        $result = $this->retrieveForProjects();
        if (\Yii::$app->requestedRoute !== 'project/workspaces') {
            $project = $this->projectRepository->retrieveForBreadcrumb($id);
            $result->add(new Breadcrumb($project->getLabel(), [
                'project/workspaces',
                'id' => $id,
            ]));
        }
        return $result;
    }

    public function retrieveForWorkspace(WorkspaceId $id): BreadcrumbCollection
    {
        $workspace = $this->workspaceRepository->retrieveForTabMenu($id);
        $result = $this->retrieveForProject($workspace->projectId());
        if (\Yii::$app->requestedRoute !== 'workspace/facilities') {
            $result->add(
                new Breadcrumb($workspace->title(), Url::to([
                    'workspace/facilities',
                    'id' => $id,
                ]))
            );
        }
        return $result;
    }

    public function retrieveForFacility(FacilityId $id): BreadcrumbCollection
    {
        $facility = $this->facilityRepository->retrieveForBreadcrumb($id);
        $result = $this->retrieveForWorkspace($facility->getWorkspaceId());
        if (\Yii::$app->requestedRoute !== 'facility/responses') {
            $result->add($facility);
        }
        return $result;
    }
}
