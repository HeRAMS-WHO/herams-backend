<?php


namespace prime\controllers\user;


use prime\components\NotificationService;
use prime\models\forms\user\UpdateEmailForm;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\mail\MailerInterface;
use yii\web\Request;
use yii\web\User;

class UpdateEmail extends Action
{
    public function run(
        UrlSigner $urlSigner,
        MailerInterface $mailer,
        User $user,
        Request $request,
        NotificationService $notificationService
    ) {
        $model = new UpdateEmailForm($mailer, $user->identity, $urlSigner);
        if ($request->isPost
            && $model->load($request->bodyParams)
            && $model->validate()
        ) {
            try {
                $model->run();
                $notificationService->info(\Yii::t('app', 'Please check your mailbox'));
            } catch (\RuntimeException $e) {
                $notificationService->error($e->getMessage());
            }
        }
        return $this->controller->render('update-email', ['model' => $model]);
    }

}