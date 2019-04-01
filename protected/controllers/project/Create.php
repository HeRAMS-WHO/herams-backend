<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class Create extends Action
{
    public function run(
        User $user,
        Request $request,
        Session $session
    ) {
        if (!$user->can(Permission::PERMISSION_ADMIN)) {
            throw new ForbiddenHttpException();
        }

        $model = new Project();

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save())
            {
                $session->setFlash(
                    'toolCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUsCCESS,
                        'text' => \Yii::t('app', "Tool {tool} is created.", ['tool' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                return $this->controller->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->controller->render('create', [
            'model' => $model
        ]);
    }
}