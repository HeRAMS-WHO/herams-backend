<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use herams\api\domain\workspace\UpdateWorkspace;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\userRole\UserRoleRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        WorkspaceRepository $workspaceRepository,
        UserRoleRepository $userRoleRepository,
        FacilityRepository $facilityRepository,
        int $id
    ): UpdateWorkspace | array {
        $workspaceId = new WorkspaceId($id);
        $userInWorkspace = $userRoleRepository
            ->countDiferentUsersInWorkspace($workspaceId);
        $workspace = $workspaceRepository->retrieveForUpdate($workspaceId);
        $amountOfFacilitiesInWorkspace = $workspaceRepository
            ->amountOfFacilitiesInWorkspace($workspaceId);
        return [
            ...$workspace->toArray(),
            'userAmountInWorkspace' => $userInWorkspace,
            'amountOfFacilitiesInWorkspace' => $amountOfFacilitiesInWorkspace,
        ];
    }
}
