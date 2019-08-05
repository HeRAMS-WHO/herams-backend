<?php


namespace prime\controllers\workspace;


use Carbon\Carbon;
use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use prime\models\forms\projects\Token;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\helpers\Console;
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

        $new = $updated = $unchanged = 0;
        $start = microtime(true);
        $limesurveyDataProvider->refreshResponsesByToken($workspace->project->base_survey_eid, $workspace->getAttribute('token'));
        foreach($limesurveyDataProvider->getResponsesByToken($workspace->project->base_survey_eid, $workspace->getAttribute('token')) as $response) {
            $key = [
                'id' => $response->getId(),
                'survey_id' => $response->getSurveyId()
            ];

            $dataResponse = Response::findOne($key) ?? new Response($key);
            $dataResponse->loadData($response->getData(), $workspace);
            if ($dataResponse->isNewRecord) {
                $new++;
                $dataResponse->save();
            } elseif (empty($dataResponse->dirtyAttributes)) {
                $unchanged++;
            } else {
                $updated++;
                $dataResponse->save();
            }
        }
        $notificationService->success(\Yii::t('app,', 'Refreshing data took {time} seconds; {new} new records, {updated} existing records, {unchanged} unchanged records', [
            'time' => number_format(microtime(true) - $start, 0),
            'new' => $new,
            'updated' => $updated,
            'unchanged' => $unchanged
        ]));

        return $this->controller->redirect($request->getReferrer());
    }

}