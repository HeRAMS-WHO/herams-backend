<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use herams\common\values\ProjectId;
use prime\components\Controller;
use prime\repositories\FormRepository;
use yii\base\Action;

class Create extends Action
{
    public function run(
        FormRepository $formRepository,
        int $project_id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        return $this->controller->render('create', [
            'form' => $formRepository->getCreateWorkspaceForm(new ProjectId($project_id)),
        ]);
    }
}
