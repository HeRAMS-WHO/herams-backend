<?php
declare(strict_types=1);

namespace prime\controllers\user;


use prime\components\NotificationService;
use prime\models\ar\User;
use prime\models\forms\user\ChangePasswordForm;
use prime\models\forms\user\UpdateEmailForm;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\mail\MailerInterface;
use yii\web\Request;

class Account extends Action
{
    public function run(
        Request $request,
        \yii\web\User $user,
        NotificationService $notificationService,
        UrlSigner $urlSigner,
        MailerInterface $mailer
    ) {
        /** @var User $model */
        $model = $user->identity;
        if ($model->load($request->getBodyParams()) && $model->save()) {
            $notificationService->success(\Yii::t('app', 'User updated'));
            return $this->controller->refresh();
        }

        $changePassword = new ChangePasswordForm($model);
        if ($changePassword->load($request->getBodyParams())
            && $changePassword->validate()) {
            $changePassword->run();
            $notificationService->success(\Yii::t('app', 'Password changed'));
            return $this->controller->refresh();
        }
        return $this->controller->render('account', [
            'model' => $model,
            'changePassword' => $changePassword,
            'changeMail' => new UpdateEmailForm($mailer, $user->identity, $urlSigner)
        ]);
    }
}