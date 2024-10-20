<?php

namespace prime\controllers\page;

use herams\common\models\Page;
use herams\common\models\PermissionOld;
use herams\common\models\Project;
use prime\components\NotificationService;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        User $user,
        int $project_id
    ) {
        $project = Project::findOne([
            'id' => $project_id,
        ]);
        if (! isset($project)) {
            throw new NotFoundHttpException();
        }

        if (! $user->can(PermissionOld::PERMISSION_MANAGE_DASHBOARD, $project)) {
            throw new ForbiddenHttpException();
        }

        $model = new Page();
        $model->project_id = $project->id;

        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(\Yii::t('app', "Page <strong>{page}</strong> created", [
                    'page' => $model->title,
                ]));

                return $this->controller->redirect([
                    'update',
                    'id' => $model->id,
                ]);
            }
        }

        return $this->controller->render('create', [
            'page' => $model,
            'project' => $project,
        ]);
    }
}
