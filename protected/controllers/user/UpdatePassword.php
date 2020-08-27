<?php


namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\forms\user\ChangePasswordForm;
use prime\models\forms\user\UpdateEmailForm;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\mail\MailerInterface;
use yii\web\Request;
use yii\web\User;

class UpdatePassword extends Action
{
    public function run(
        User $user,
        Request $request,
        NotificationService $notificationService
    ) {
        $model = new ChangePasswordForm($user->identity);
        if ($request->isPost
            && $model->load($request->bodyParams)
            && $model->validate()
        ) {
            $model->run();
            $notificationService->success(\Yii::t('app', 'Password changed'));
            return $this->controller->redirect(['/user/account']);
        }
        return $this->controller->render('update-password', ['model' => $model]);
    }
}
