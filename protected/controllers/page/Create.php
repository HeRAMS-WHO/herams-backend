<?php


namespace prime\controllers\page;


use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class Create extends Action
{

    public function run(
        Request $request,
        Session $session,
        User $user,
        int $project_id

    ) {
        $project = Project::findOne(['id' => $project_id]);
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }

        if (!$user->can(Permission::PERMISSION_ADMIN, $project)) {
            throw new ForbiddenHttpException();
        }


        $model = new Page();
        $model->tool_id=  $project->id;

        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'toolUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Page <strong>{$model->title}</strong> created",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                return $this->controller->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->controller->render('create', [
            'page' => $model,
            'project' => $project
        ]);
    }

}