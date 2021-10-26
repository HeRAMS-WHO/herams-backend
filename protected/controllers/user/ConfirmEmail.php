<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\forms\user\ConfirmEmailForm;
use yii\base\Action;
use yii\web\GoneHttpException;
use yii\web\Request;
use yii\web\User;

class ConfirmEmail extends Action
{
    public function run(
        User $user,
        Request $request,
        NotificationService $notificationService,
        string $email,
        string $old_hash
    ) {
        $model = new ConfirmEmailForm(
            $user->identity,
            $email,
            $old_hash
        );

        if (!$model->validate()) {
            throw new GoneHttpException($model->getFirstError('newMail') ?? $model->getFirstError('oldHash'));
        }

        if ($request->isPost) {
            $model->run();
            $notificationService->success(\Yii::t('app', 'Your email address has been updated'));
            return $this->controller->goHome();
        }

        return $this->controller->render('confirm-email', ['model' => $model]);
    }
}
