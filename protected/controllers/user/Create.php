<?php


namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\forms\user\CreateUserForm;
use yii\base\Action;
use yii\web\Request;

class Create extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        string $email
    ) {
        $model = new CreateUserForm();
        $model->email = $email;
        if ($model->load($request->getBodyParams())) {
            $model->run();
            $notificationService->success(\Yii::t('app', "Your account has been created"));
            return $this->controller->goHome();
        }

        return $this->controller->render('create', [
            'model' => $model
        ]);
    }
}
