<?php
declare(strict_types=1);

namespace prime\controllers\user;


use prime\components\NotificationService;
use prime\models\forms\user\RequestResetForm;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\caching\CacheInterface;
use yii\mail\MailerInterface;
use yii\web\Request;

class RequestReset extends Action
{
    public function run(
        Request $request,
        MailerInterface $mailer,
        NotificationService $notificationService,
        UrlSigner $urlSigner,
        CacheInterface $cache
    ) {
        $model = new RequestResetForm($cache);

        if ($model->load($request->getBodyParams())
            && $model->validate()
            && $model->send($mailer, $urlSigner)
        ) {
            $notificationService->success(\Yii::t('app', "A password reset email has beent sent to your address"));
            return $this->controller->goHome();
        }
        return $this->controller->render('request-reset', ['model' => $model]);
    }
}