<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\forms\user\UpdatePasswordForm;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Password extends Action
{
    public function run(
        User $user,
        Request $request,
        NotificationService $notificationService
    ) {
        $model = new UpdatePasswordForm($user->identity);
        if (
            $request->isPost
            && $model->load($request->bodyParams)
            && $model->validate()
        ) {
            $model->run();
            $notificationService->success(\Yii::t('app', 'Password changed'));
            return $this->controller->redirect(['/user/profile']);
        }

        return $this->controller->render(
            'password',
            [
                'model' => $model
            ]
        );
    }
}
