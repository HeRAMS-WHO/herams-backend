<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use prime\models\permissions\Permission;
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
        $project = Project::find()->with('pages')->where(['id' => $id])->one();
        if (!isset($project)) {
            throw new NotFoundHttpException('Project not found');
        }
        if (!$user->can(Permission::PERMISSION_ADMIN, $project)) {
            throw new ForbiddenHttpException();
        }


        $response->format = Response::FORMAT_JSON;
        $response->data = $project->exportDashboard();
        $response->setDownloadHeaders("Dashboard {$project->title}.json");
        return $response;
    }

}