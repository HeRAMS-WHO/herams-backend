<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Permission;
use herams\common\values\ProjectId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\models\ar\read\Project;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use function iter\toArray;

class Pages extends Action
{
    public function run(
        BreadcrumbService $breadcrumbService,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $model = Project::findOne([
            'id' => $id,
        ]);

        $projectId = new ProjectId($id);
        $accessCheck->requirePermission($model, Permission::PERMISSION_MANAGE_DASHBOARD);
        $this->controller->view->breadcrumbCollection->add(...toArray($breadcrumbService->retrieveForProject($projectId)->getIterator()));
        return $this->controller->render('pages', [
            'project' => $model,
            'dataRoute' => ['/api/project/pages', 'id' => $projectId],
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getPages(),
            ]),
        ]);
    }
}
