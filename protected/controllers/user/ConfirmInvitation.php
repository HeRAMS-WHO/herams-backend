<?php

namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\forms\user\ConfirmInvitationForm;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\web\Request;

/**
 * Class ConfirmInvitation
 * @package prime\controllers\user
 */
class ConfirmInvitation extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        AuthManager $abacManager,
        string $email,
        string $subject,
        int $subjectId,
        array $permissions
    ) {
        $model = new ConfirmInvitationForm(
            $abacManager,
            $email,
            $subject,
            $subjectId,
            $permissions
        );

        if ($request->isPost && $model->load($request->bodyParams) && $model->validate()) {
            $model->createAccount();
            $notificationService->success(\Yii::t('app', "Your account has been created and invitation was accepted."));
            return $this->controller->goHome();
        }

        return $this->controller->render(
            'confirm-invitation',
            [
                'model' => $model,
            ]
        );
    }
}
