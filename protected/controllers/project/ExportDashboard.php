<?php

namespace prime\controllers\project;

use herams\common\models\PermissionOld;
use herams\common\models\Project;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\User;

class ExportDashboard extends Action
{
    public function run(
        Response $response,
        User $user,
        int $id
    ) {
        $this->controller->layout = \prime\components\Controller::LAYOUT_ADMIN;
        $project = Project::find()->with('mainPages')->where([
            'id' => $id,
        ])->one();
        if (! isset($project)) {
            throw new NotFoundHttpException('Project not found');
        }
        if (! $user->can(PermissionOld::PERMISSION_MANAGE_DASHBOARD, $project)) {
            throw new ForbiddenHttpException();
        }

        $response->format = Response::FORMAT_JSON;
        $response->data = $project->exportDashboard();
        $response->setDownloadHeaders("Dashboard {$project->title}.json");
        return $response;
    }
}
