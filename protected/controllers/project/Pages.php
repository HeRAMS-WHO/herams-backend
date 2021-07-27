<?php


namespace prime\controllers\project;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Pages extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = \prime\components\Controller::LAYOUT_ADMIN_TABS;
        $model = Project::findOne(['id' => $id]);

        $accessCheck->requirePermission($model, Permission::PERMISSION_MANAGE_DASHBOARD);


        return $this->controller->render('pages', [
            'project' => $model,
            'dataProvider' => new ActiveDataProvider(['query' => $model->getAllPages()])
        ]);
    }
}
