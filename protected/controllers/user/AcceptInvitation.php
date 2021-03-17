<?php

namespace prime\controllers\user;

use Carbon\Carbon;
use prime\components\NotificationService;
use prime\models\forms\user\AcceptInvitationForm;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\mail\MailerInterface;
use yii\web\Request;

class AcceptInvitation extends Action
{
    public function run(
        Request $request,
        MailerInterface $mailer,
        NotificationService $notificationService,
        UrlSigner $urlSigner,
        string $email,
        string $subject,
        string $subjectId,
        string $permissions
    ) {
        $permissions = explode(',', $permissions);
        $model = new AcceptInvitationForm(
            $email,
            $subject,
            $subjectId,
            $permissions
        );

        if ($request->isPost && $model->load($request->bodyParams) && $model->validate()) {
            $url = [
                '/user/confirm-invitation',
                'email' => $email,
                'subject' => $subject,
                'subjectId' => $subjectId,
                'permissions' => implode(',', $permissions),
            ];
            $urlSigner->signParams($url, false, Carbon::tomorrow());

            if ($model->hasEmailChanged()) {
                $model->sendConfirmationEmail($mailer, $urlSigner);
                $notificationService->success(\Yii::t('app', 'A verification email has been sent to your address'));
                return $this->controller->goHome();
            } else {
                return $this->controller->redirect($url);
            }
        }

        return $this->controller->render(
            'accept-invitation',
            [
                'model' => $model,
            ]
        );
    }
}
