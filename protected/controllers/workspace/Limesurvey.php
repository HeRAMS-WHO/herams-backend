<?php


namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class Limesurvey extends Action
{

    public function run(
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspace = Workspace::findOne(['id' => $id]);
        $accessCheck->requirePermission($workspace, Permission::PERMISSION_SURVEY_DATA);

        return $this->controller->render('limesurvey', [
            'model' => $workspace
        ]);
    }
}
