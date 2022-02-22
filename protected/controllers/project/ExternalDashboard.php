<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\repositories\ProjectRepository;
use prime\values\ProjectId;
use yii\base\Action;

class ExternalDashboard extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        int $id
    ) {
        $this->controller->layout = 'css3-grid';
        $project = $projectRepository->retrieveForExternalDashboard(new ProjectId($id));

        return $this->controller->render('external-dashboard', [
            'project' => $project
        ]);
    }
}
