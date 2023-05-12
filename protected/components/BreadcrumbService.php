<?php

declare(strict_types=1);

namespace prime\components;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use prime\helpers\Icon;
use prime\objects\Breadcrumb;
use prime\objects\BreadcrumbCollection;
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
            $result->add(new Breadcrumb(Icon::project() . ' ' . $project->getLabel(), [
                'project/workspaces',
                'encode' => false,
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
                new Breadcrumb(Icon::workspace() . ' ' . $workspace->title(), Url::to([
                    'workspace/facilities',
                    'encode' => false,
                    'id' => $id,
                ]))
            );
        }
        return $result;
    }

    public function retrieveForFacility(FacilityId $id): BreadcrumbCollection
    {
        $facility = $this->facilityRepository->retrieveForTabMenu($id);
        $result = $this->retrieveForWorkspace($facility->getWorkspaceId());
        $result->add(new Breadcrumb(
            Icon::healthFacility() . $facility->getTitle(),
            Url::to([
                'facility/responses',
                'id' => $id,
            ])
        ));
        return $result;
    }
}
