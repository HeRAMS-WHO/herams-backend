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
        if (!$user->can(Permission::PERMISSION_LIMESURVEY, $workspace)) {
            throw new ForbiddenHttpException();
        }

        $new = $updated = $unchanged = $failed = 0;
        $start = microtime(true);
        $ids = [];
        foreach($limesurveyDataProvider->refreshResponsesByToken($workspace->project->base_survey_eid, $workspace->getAttribute('token')) as $response) {
            $ids[] = $response->getId();
            $key = [
                'id' => $response->getId(),
                'workspace_id' => $workspace->id
            ];

            $dataResponse = Response::findOne($key) ?? new Response($key);
            $dataResponse->loadData($response->getData(), $workspace);
            if ($dataResponse->isNewRecord && $dataResponse->save()) {
                $new++;
            } elseif (empty($dataResponse->dirtyAttributes)) {
                $unchanged++;
            } elseif ($dataResponse->save()) {
                $updated++;
            } else {
                $failed++;
            }
        }
        // Check for deleted responses as well.
        $deleted = Response::deleteAll([
            'and',
            ['workspace_id' => $workspace->id],
            ['not', ['id' => $ids]]
        ]);
        $notificationService->success(\Yii::t('app', 'Refreshing data took {time} seconds; 
        {new} new records, 
        {updated} existing records, 
        {deleted} deleted records, 
        {unchanged} unchanged records, 
        {failed} invalid records', [
            'time' => number_format(microtime(true) - $start, 0),
            'new' => $new,
            'updated' => $updated,
            'deleted' => $deleted,
            'unchanged' => $unchanged,
            'failed' => $failed
        ]));

        return $this->controller->redirect($request->getReferrer());
    }

}