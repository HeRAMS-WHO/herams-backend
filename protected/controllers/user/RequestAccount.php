<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\forms\user\RequestAccountForm;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\caching\CacheInterface;
use yii\mail\MailerInterface;
use yii\web\Request;
use yii\web\User;

class RequestAccount extends Action
{
    public function run(
        Request $request,
        User $user,
        MailerInterface $mailer,
        UrlSigner $urlSigner,
        NotificationService $notificationService,
        CacheInterface $cache
    ) {
        if (!$user->getIsGuest()) {
            return $this->controller->goHome();
        }

        $model = new RequestAccountForm($cache);
        if (
            $model->load($request->getBodyParams())
            && $model->validate()
            && $model->send($mailer, $urlSigner)
        ) {
            $notificationService->success(\Yii::t('app', "A verification email has been sent to your address"));
        } elseif ($model->hasErrors('email')) {
            $notificationService->error($model->getFirstError('email'));
        }

        return $this->controller->goHome();
    }
}
