<?php

declare(strict_types=1);

namespace herams\api\controllers\user;

use herams\common\domain\userRole\UserRoleRepository;
use herams\common\values\UserId;
use yii\base\Action;

final class Roles extends Action
{
    public function run(
        UserRoleRepository $userRoleRepository,
        int $id
    ): array {
        $userId = new UserId($id);
        return $userRoleRepository->retrieveUserRolesForAUser($userId);
    }
}
