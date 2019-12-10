<?php


namespace prime\controllers\project;


use prime\models\search\Project as ProjectSearch;
use yii\base\Action;
use yii\web\Request;

class Index extends Action
{
    public function run(
        Request $request
    )
    {
        $projectSearch = new ProjectSearch();

        $projectProvider = $projectSearch->search($request->queryParams);
        return $this->controller->render('index', [
            'projectSearch' => $projectSearch,
            'projectProvider' => $projectProvider
        ]);
    }
}