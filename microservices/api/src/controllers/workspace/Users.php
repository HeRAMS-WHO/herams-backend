<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use herams\common\domain\userRole\UserRoleRepository;
use herams\common\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

final class Users extends Action
{
    public function run(
        Request $request,
        UserRoleRepository $userRoleRepository,
        Response $response,
        int $id
    ) {
        $workspaceId = new WorkspaceId($id);
        $response->data = $userRoleRepository->retrieveUserRolesInWorkspace(
            $workspaceId
        );
        return $response;
    }
}
