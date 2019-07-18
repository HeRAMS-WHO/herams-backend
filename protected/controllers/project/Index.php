<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Index extends Action
{
    public function run()
    {
        $projectProvider = new ActiveDataProvider([
            'query' => Project::find()
                ->with('workspaces')
        ]);

        /** @var Project $model */
        foreach($projectProvider->getModels() as $model) {
            \Yii::beginProfile($model->title, 'load data');
            $model->getHeramsResponses();
            \Yii::endProfile($model->title, 'load data');
        }

        return $this->controller->render('index', [
            'projectProvider' => $projectProvider
        ]);
    }
}