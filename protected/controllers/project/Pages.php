<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\Controller;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Pages extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $model = Project::findOne(['id' => $id]);

        $accessCheck->requirePermission($model, Permission::PERMISSION_MANAGE_DASHBOARD);

        return $this->controller->render('pages', [
            'project' => $model,
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getPages()
            ])
        ]);
    }
}
