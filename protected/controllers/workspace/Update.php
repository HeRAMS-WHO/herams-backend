<?php


namespace prime\controllers\workspace;


use prime\models\forms\projects\CreateUpdate;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class Update extends Action
{

    public function run(
        User $user,
        Request $request,
        Session $session,
        $id
    )
    {
        $model = CreateUpdate::loadOne($id, [], Permission::PERMISSION_ADMIN);
        if ($user->can('admin')) {
            $model->scenario = 'admin-update';
        } else {
            $model->scenario = 'update';
        }
        if($request->isPut) {
            if($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'projectUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Project <strong>{modelName}</strong> has been updated.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->controller->redirect(['project/workspaces', 'id' => $model->project->id]);
            }
        }

        return $this->controller->render('update', [
            'model' => $model
        ]);
    }


}