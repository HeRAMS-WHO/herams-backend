<?php


namespace prime\controllers\project;

use prime\models\ar\Project;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class Limesurvey extends Action
{

    public function run(
        int $id
    ) {
        $this->controller->layout = 'admin-screen';
        $project = Project::findOne(['id' => $id]);
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }
        return $this->controller->render('limesurvey', [
            'project' => $project
        ]);
    }
}
