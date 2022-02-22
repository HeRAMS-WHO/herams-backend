<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\search\Response as ResponseSearch;
use yii\base\Action;
use yii\web\Request;

class Responses extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspace = WorkspaceForLimesurvey::findOne(['id' => $id]);

        $accessCheck->requirePermission($workspace, Permission::PERMISSION_READ);

        $responseSearch = new ResponseSearch($workspace);
        return $this->controller->render('responses', [
            'responseSearch' => $responseSearch,
            'responseProvider' => $responseSearch->search($request->queryParams),
            'workspace' => $workspace
        ]);
    }
}
