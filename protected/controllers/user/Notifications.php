<?php
declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\UserNotificationService;
use yii\base\Action;
use yii\web\User as UserComponent;

class Notifications extends Action
{
    public function run(
        UserComponent $user,
        UserNotificationService $userNotificationService
    ) {
        $notificationsDataprovider = $userNotificationService->getNotifications($user->identity);

        return $this->controller->render(
            'notifications',
            [
                'userNotificationsDataprovider' => $notificationsDataprovider
            ]
        );
    }
}
