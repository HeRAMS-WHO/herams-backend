<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class Summary extends Action
{
    public function run(int $id)
    {
        $this->controller->layout = 'base';
        $project = Project::find()->with('pages')->where(['id' => $id])->one();
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }

        switch($project->status) {
            case Project::STATUS_TARGET:
                $view = 'summary-target';
                break;
            default:
                $view = 'summary';
        }
        return $this->controller->render($view, [
            'project' => $project,
        ]);
    }

}