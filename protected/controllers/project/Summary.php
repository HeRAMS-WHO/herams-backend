<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use prime\objects\HeramsResponse;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class Summary extends Action
{

    public function run(int $id)
    {
        $this->controller->layout = 'base';
        $project = Project::findOne(['id' => $id]);
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }

        $heramsResponses = [];
        foreach($project->getResponses() as $response) {
            try {
                $heramsResponses[] = new HeramsResponse($response, $project->getMap());
            } catch (\InvalidArgumentException $e) {
                // Ignore invalid responses for now.
            }
        }

        return $this->controller->render('summary', [
            'project' => $project,
            'heramsResponses' => $heramsResponses
        ]);
    }

}