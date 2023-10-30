<?php

declare(strict_types=1);

namespace herams\api\controllers\userRole;

use herams\common\domain\userRole\UserRoleRepository;
use herams\common\values\ProjectId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

final class Index extends Action
{
    public function run(
        UserRoleRepository $userRoleRepository,
        Request $request,
        Response $response,
        int $id
    ) {
        $response->data = $userRoleRepository->retrieveUserRolesInProject(
            new ProjectId($id)
        );
        return $response;
    }
}
