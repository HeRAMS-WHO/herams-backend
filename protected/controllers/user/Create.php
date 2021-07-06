<?php
declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\ar\User;
use prime\models\forms\user\CreateForm;
use yii\base\Action;
use yii\web\Request;

class Create extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        string $email
    ) {
        $user = new User();
        $model = new CreateForm($user);
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
