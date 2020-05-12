<?php


namespace prime\controllers\workspace;


use prime\models\ar\Workspace;
use prime\models\search\Response as ResponseSearch;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Responses extends Action
{
    public function run(
        Request $request,
        User $user,
        AuthManager $abacManager,
        int $id
    ) {
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }

        $responseSearch = new ResponseSearch($workspace);
        return $this->controller->render('responses', [
            'responseSearch' => $responseSearch,
            'responseProvider' => $responseSearch->search($request->queryParams),
            'workspace' => $workspace
        ]);
    }
}