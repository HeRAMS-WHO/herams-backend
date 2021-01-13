<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use yii\base\Action;
use yii\web\Request;

class ImportDashboard extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = \prime\components\Controller::LAYOUT_ADMIN_TABS;
        /** @var Project|null $project */
        $project = Project::find()->where(['id' => $id])->one();

        $accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_DASHBOARD);

        $model = new \prime\models\forms\ImportDashboard($project);

        if ($request->isPost
            && $model->load($request->bodyParams)
            && $model->validate()
        ) {
            $model->run();
            return $this->controller->redirect(['project/pages', 'id' => $project->id]);
        }


        return $this->controller->render('import-dashboard', ['model' => $model, 'project' => $project]);
    }
}
