<?php
declare(strict_types=1);

namespace prime\controllers\user;

use Carbon\Carbon;
use prime\components\NotificationService;
use prime\models\forms\user\AcceptInvitationForm;
use SamIT\abac\AuthManager;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\caching\CacheInterface;
use yii\mail\MailerInterface;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\User;

class AcceptInvitation extends Action
{
    public $hmacUsedKey = 'userAcceptInvitation-';

    public function run(
        Request $request,
        MailerInterface $mailer,
        NotificationService $notificationService,
        UrlSigner $urlSigner,
        CacheInterface $cache,
        AuthManager $abacManager,
        User $user,
        string $email,
        string $subject,
        string $subjectId,
        string $permissions,
        string $h,
        int $e
    ) {
        if ($cache->exists($this->hmacUsedKey . $h)) {
            throw new ForbiddenHttpException('Link has already been used. Request a new one if needed.');
        }

        $permissions = explode(',', $permissions);
        $model = new AcceptInvitationForm(
            $user,
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
            $cache->set($this->hmacUsedKey . $h, true, $e - Carbon::now()->timestamp);

            if ($model->loggedInAccept) {
                $model->grantLoggedInUser($abacManager);
                $notificationService->success(\Yii::t('app', 'The invitation was accepted'));
                return $this->controller->goHome();
            } elseif ($model->hasEmailChanged()) {
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
