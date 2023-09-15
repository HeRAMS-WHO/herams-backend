<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use prime\components\Controller;
use prime\repositories\FormRepository;
use yii\base\Action;

class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        FormRepository $formRepository,
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $accessCheck->requireGlobalPermission(PermissionOld::PERMISSION_CREATE_PROJECT);

        return $this->controller->render('create-surveyjs', [
            'form' => $formRepository->getCreateProjectForm(),
        ]);
    }
}
