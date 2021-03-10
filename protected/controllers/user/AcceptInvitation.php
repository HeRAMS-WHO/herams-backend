<?php

namespace prime\controllers\user;

use Carbon\Carbon;
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
        UrlSigner $urlSigner,
        string $email,
        string $subject,
        int $subjectId,
        array $permissions
    ) {
        $model = new AcceptInvitationForm(
            $mailer,
            $urlSigner,
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
