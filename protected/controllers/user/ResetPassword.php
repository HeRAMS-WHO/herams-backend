<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\ar\User;
use prime\models\forms\user\ResetPasswordForm;
use yii\base\Action;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\UnauthorizedHttpException;

class ResetPassword extends Action
{
    /**
     * @return string
     * @throws HttpException
     * @throws UnauthorizedHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function run(
        Request $request,
        NotificationService $notificationService,
        int $id,
        int $crc
    ) {
        /** @var ?User $user */
        $user = User::find()->andWhere([
            'id' => $id,
        ])->one();
        if (! isset($user)) {
            throw new NotFoundHttpException(\Yii::t('app', "User not found"));
        }

        if (crc32($user->password_hash ?? '') !== $crc) {
            throw new UnauthorizedHttpException(\Yii::t('app', 'This link is no longer valid; your password has been changed since it was issued'));
        }

        $model = new ResetPasswordForm($user);
        if (
            $model->load($request->bodyParams)
            && $model->validate()
        ) {
            $model->resetPassword();
            $notificationService->success(\Yii::t('app', 'Your password has been reset'));
            return $this->controller->goHome();
        }
        return $this->controller->render('reset-password', [
            'model' => $model,
        ]);
    }
}
