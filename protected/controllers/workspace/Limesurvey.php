<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use yii\base\Action;

class Limesurvey extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspace = WorkspaceForLimesurvey::findOne(['id' => $id]);
        $accessCheck->requirePermission($workspace, Permission::PERMISSION_LIST_FACILITIES);

        return $this->controller->render('limesurvey', [
            'model' => $workspace
        ]);
    }
}
