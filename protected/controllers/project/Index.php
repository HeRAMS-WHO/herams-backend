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
                ->withFields('workspaceCount', 'facilityCount', 'responseCount')
                ->with('workspaces')
        ]);

        return $this->controller->render('index', [
            'projectProvider' => $projectProvider
        ]);
    }
}