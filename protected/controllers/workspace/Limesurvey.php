<?php


namespace prime\controllers\workspace;


use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use yii\base\Action;

class Limesurvey extends Action
{

    public function run(int $id)
    {
        $model = Workspace::loadOne($id, [], Permission::PERMISSION_ADMIN);
        return $this->controller->render('limesurvey', [
            'model' => $model
        ]);
    }
}