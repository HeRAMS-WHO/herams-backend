<?php

declare(strict_types=1);

namespace herams\api\controllers\user;

use herams\common\domain\user\UserRepository;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Permission;
use yii\base\Action;

final class Index extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        UserRepository $userRepository,
    ) {
        $accessCheck->requireGlobalPermission(Permission::PERMISSION_LIST_USERS);
        return $userRepository->retrieveAll();
    }
}
