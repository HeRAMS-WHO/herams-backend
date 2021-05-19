<?php
declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\ar\User;
use yii\base\Action;
use yii\web\Request;

class Newsletters extends Action
{
    public function run(
        Request $request,
        \yii\web\User $user,
        NotificationService $notificationService
    ) {
        /** @var User $model */
        $model = $user->identity;
        if ($model->load($request->getBodyParams()) && $model->save()) {
            $notificationService->success(\Yii::t('app', 'User updated'));
            return $this->controller->refresh();
        }

        return $this->controller->render('profile', [
            'model' => $model,
        ]);
    }
}
