<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\repositories\ProjectRepository;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

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
