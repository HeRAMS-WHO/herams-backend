<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Index extends Action
{
    public function run()
    {
        $projectProvider = new ActiveDataProvider([
            'query' => Project::find()->userCan(Permission::PERMISSION_READ)
        ]);

        return $this->controller->render('index', [
            'toolsDataProvider' => $projectProvider
        ]);
    }
}