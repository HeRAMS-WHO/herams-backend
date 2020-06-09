<?php


namespace prime\controllers\project;


use prime\components\NotificationService;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Pages extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        User $user,
        int $id
    )
    {
        $model = Project::findOne(['id' => $id]);
        if (!isset($model)) {
            throw new NotFoundHttpException();
        }

        if (!$user->can(Permission::PERMISSION_MANAGE_DASHBOARD, $model)) {
            throw new ForbiddenHttpException();
        }


        return $this->controller->render('pages', [
            'model' => $model,
            'dataProvider' => new ActiveDataProvider(['query' => $model->getAllPages()])
        ]);
    }
}