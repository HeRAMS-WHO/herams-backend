<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use herams\common\values\WorkspaceId;
use prime\components\Controller;
use prime\repositories\FormRepository;
use yii\base\Action;

class Update extends Action
{
    public function run(
        FormRepository $formRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        return $this->controller->render('create', [
            'form' => $formRepository->getUpdateWorkspaceForm(new WorkspaceId($id)),
        ]);
    }
}
