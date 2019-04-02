<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use yii\base\Action;
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
        $model = new Project();

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save())
            {
                $session->setFlash(
                    'toolCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
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