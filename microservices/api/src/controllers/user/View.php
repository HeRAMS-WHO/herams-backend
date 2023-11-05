<?php

declare(strict_types=1);

namespace herams\api\controllers\user;

use herams\common\domain\user\UserRepository;
use yii\base\Action;
use yii\web\User;

final class View extends Action
{
    public function run(
        UserRepository $userRepository,
        User $user,
        int $id = null
    ): \herams\common\domain\user\User|array{
        $data = [];
        $user = $userRepository->retrieveOrThrow($id ?? $user->id);
        $data['creator'] = $user->creator?->toArray();
        $data['updater'] = $user->updater?->toArray();
        return array_merge($data, $user->toArray());
    }
}
