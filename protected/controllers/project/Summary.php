<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use prime\objects\HeramsResponse;
use yii\base\Action;
use yii\filters\PageCache;
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
        return $this->controller->render('summary', [
            'project' => $project,
        ]);
    }

}