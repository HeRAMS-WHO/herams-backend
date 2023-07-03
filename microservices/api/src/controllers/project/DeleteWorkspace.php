<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\accessRequest\AccessRequestRepository;
use herams\common\domain\favorite\FavoriteRepository;
use herams\common\domain\page\PageRepository;
use herams\common\domain\permission\PermissionRepository;
use herams\common\domain\project\ProjectRepository;
use yii\base\Action;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use herams\common\values\FacilityId;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\facility\Facility;
use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;



class DeleteWorkspace extends Action
{
    public function run(
        WorkspaceRepository $workspaceRepository,
        FacilityRepository $facilityRepository,
        SurveyResponseRepository $surveyResponseRepository,
        AccessRequestRepository $accessRequestRepository,
        FavoriteRepository $favoriteRepository,
        PermissionRepository $permissionRepository,
        ProjectRepository $projectRepository,
        PageRepository $pageRepository,
        int $id
    ) {
        $projectId = new ProjectId($id);
        $projectRepository->emptyProject(
            $projectId,
            $workspaceRepository,
            $facilityRepository,
            $surveyResponseRepository,
            $accessRequestRepository,
            $favoriteRepository,
            $permissionRepository,
            $pageRepository
        );
        return true;
    }
}
