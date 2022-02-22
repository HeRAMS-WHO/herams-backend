<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\Controller;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use yii\base\Action;
use yii\web\Request;

class ImportDashboard extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        /** @var Project|null $project */
        $project = Project::findOne(['id' => $id]);

        $accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_DASHBOARD);

        $model = new \prime\models\forms\ImportDashboard($project);

        if (
            $request->isPost
            && $model->load($request->bodyParams)
            && $model->validate()
        ) {
            $model->run();
            return $this->controller->redirect(['project/pages', 'id' => $project->id]);
        }

        return $this->controller->render('import-dashboard', ['model' => $model, 'project' => $project]);
    }
}
