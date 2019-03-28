<?php


namespace prime\controllers\workspace;


use prime\models\ar\Project;
use prime\models\forms\workspace\CreateUpdate;
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
        Session $session,
        int $project_id
    ) {
        $project = Project::loadOne($project_id);
        if (!$user->can(Permission::PERMISSION_INSTANTIATE, $project)) {
            throw new ForbiddenHttpException();
        }

        $model = new CreateUpdate();
        $model->scenario = CreateUpdate::SCENARIO_CREATE;
        $model->tool_id = $project->id;

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'workspaceCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Workspace <strong>{modelName}</strong> has been updated.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->controller->redirect(['project/workspaces', 'id' => $model->project->id]);
            }
        }

        return $this->controller->render('create', [
            'model' => $model
        ]);
    }


}