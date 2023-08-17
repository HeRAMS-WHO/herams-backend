<?php

declare(strict_types=1);

namespace prime\controllers\user;

use herams\common\domain\user\User;
use prime\components\NotificationService;
use yii\base\Action;
use yii\web\Request;
use yii\web\User as UserComponent;

class Profile extends Action
{
    public function run(
        Request $request,
        UserComponent $user,
        NotificationService $notificationService,
    ) {
        /** @var User $model */
        $model = $user->identity;
        if ($model->load($request->getBodyParams()) && $model->save()) {
            $notificationService->success(\Yii::t('app', 'User updated'));
            return $this->controller->refresh();
        }

        return $this->controller->render('react-profile', [
            'model' => $model,
        ]);
    }
}
