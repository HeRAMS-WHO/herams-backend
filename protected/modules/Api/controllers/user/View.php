<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\user;

use prime\repositories\UserRepository;
use yii\base\Action;
use yii\web\User;

final class View extends Action
{
    public function run(
        UserRepository $userRepository,
        User $user,
        int $id = null
    )
    {
        return $userRepository->retrieve($user->id);
    }
}
