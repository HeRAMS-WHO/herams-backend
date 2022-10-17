<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\user;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\repositories\UserRepository;
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
