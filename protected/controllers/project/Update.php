<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\Request;
use yii\web\Session;

class Update extends Action
{
    public function run(Request $request, Session $session, $id)
    {
        $model = Project::loadOne($id);
        $model->validate();
        if ($request->isPut) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'toolUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Tool <strong>{$model->title}</strong> is updated.",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                return $this->controller->refresh();
            }
        }

        return $this->controller->render('update', [
            'model' => $model
        ]);
    }
}