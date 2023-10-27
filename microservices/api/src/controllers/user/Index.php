<?php

declare(strict_types=1);

namespace herams\api\controllers\user;

use herams\common\domain\user\UserRepository;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use herams\common\models\User;
use yii\base\Action;

final class Index extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        UserRepository $userRepository,
    ) {
        $accessCheck->requireGlobalPermission(PermissionOld::PERMISSION_LIST_USERS);
        return $userRepository->retrieveAll();
    }
}
