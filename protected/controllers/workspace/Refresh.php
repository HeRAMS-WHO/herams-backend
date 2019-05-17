<?php


namespace prime\controllers\workspace;


use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\models\ar\Workspace;
use prime\models\forms\projects\Token;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Refresh extends Action
{
    public function run(
        Request $request,
        User $user,
        NotificationService $notificationService,
        LimesurveyDataProvider $limesurveyDataProvider,
        int $id
    ) {
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!(
            $user->can(Permission::PERMISSION_ADMIN, $workspace)
            || $user->can(Permission::PERMISSION_WRITE, $workspace->project)
        )) {
            throw new ForbiddenHttpException();
        }

        $start = microtime(true);
        $limesurveyDataProvider->refreshResponsesByToken($workspace->project->base_survey_eid, $workspace->getAttribute('token'));
        $notificationService->success(\Yii::t('app,', 'Refreshing data took {time} seconds', [
            'time' => number_format(microtime(true) - $start, 0)
        ]));

        return $this->controller->redirect($request->getReferrer());
    }

}