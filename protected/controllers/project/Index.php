<?php


namespace prime\controllers\project;

use prime\models\search\Project as ProjectSearch;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Index extends Action
{
    public function run(
        Request $request,
        User $user
    ) {
        $projectSearch = new ProjectSearch();

        $projectProvider = $projectSearch->search($request->queryParams, $user);
        return $this->controller->render('index', [
            'projectSearch' => $projectSearch,
            'projectProvider' => $projectProvider
        ]);
    }
}
