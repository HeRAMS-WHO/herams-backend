<?php

declare(strict_types=1);

namespace herams\api\controllers\userRole;

use herams\common\domain\userRole\UserRoleRepository;
use herams\common\values\userRole\UserRoleId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

final class Delete extends Action
{
    public function run(
        UserRoleRepository $userRoleRepository,
        Request $request,
        Response $response,
        int $id
    ) {
        $userRoleRepository->delete(
            new UserRoleId($id)
        );
        $response->setStatusCode(204);
        return $response;
    }
}
