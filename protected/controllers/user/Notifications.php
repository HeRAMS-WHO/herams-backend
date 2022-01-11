<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\repositories\UserNotificationRepository;
use yii\base\Action;
use yii\web\User as UserComponent;

class Notifications extends Action
{
    public function run(
        UserComponent $user,
        UserNotificationRepository $userNotificationService
    ) {
        $notificationsDataprovider = $userNotificationService->getNotificationsForUser($user->identity);

        return $this->controller->render(
            'notifications',
            [
                'model' => $user->identity,
                'userNotificationsDataprovider' => $notificationsDataprovider,
            ]
        );
    }
}
