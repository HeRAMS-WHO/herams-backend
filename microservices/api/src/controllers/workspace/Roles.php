<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use herams\common\domain\role\RoleRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

final class Roles extends Action
{
    public function run(
        Request $request,
        RoleRepository $roleRepository,
        WorkspaceRepository $workspaceRepository,
        Response $response,
        int $id
    ) {
        $workspaceId = new WorkspaceId($id);
        $response->data = $roleRepository->retrieveRolesInWorkspaces(
            $workspaceId,
            $workspaceRepository
        );
        return $response;
    }
}
