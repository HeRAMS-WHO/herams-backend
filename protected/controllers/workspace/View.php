<?php

namespace prime\controllers\workspace;

use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class View extends Action
{
    public function run(
        User $user,
        int $id
    ) {
        $this->controller->layout = 'css3-grid';
        $workspace = WorkspaceForLimesurvey::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_SURVEY_DATA, $workspace)) {
            throw new ForbiddenHttpException();
        }

        return $this->controller->render('view', [
            'model' => $workspace
        ]);
    }
}
