<?php


namespace prime\controllers\workspace;

use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\helpers\LimesurveyDataLoader;
use prime\models\ar\Permission;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
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
        LimesurveyDataLoader $loader,
        int $id
    ) {
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_SURVEY_DATA, $workspace)) {
            throw new ForbiddenHttpException();
        }

        $new = $updated = $saved = $unchanged = $failed = 0;
        $start = microtime(true);
        $ids = [];
        $failedIds = [];
        foreach ($limesurveyDataProvider->refreshResponsesByToken($workspace->project->base_survey_eid, $workspace->getAttribute('token')) as $response) {
            $ids[] = $response->getId();
            $key = [
                'id' => $response->getId(),
                'workspace_id' => $workspace->id
            ];

            $dataResponse = Response::findOne($key) ?? new Response($key);
            $loader->loadData($response->getData(), $workspace, $dataResponse);
            if ($dataResponse->isNewRecord && $dataResponse->save()) {
                $new++;
                $saved++;
            } elseif (empty($dataResponse->dirtyAttributes)) {
                $unchanged++;
            } elseif ($dataResponse->save()) {
                $updated++;
                $saved++;
            } else {
                $failed++;
                $failedIds[] = $dataResponse->getId();
            }
        }

        // everything has been saved
        if (count($ids) == ($saved + $unchanged)) {
            $workspace->logWorkspaceSync(\Yii::$app->user->getId(), Workspace::SYNC_OK);
        } else if ($failed && $saved) {
            $sync_error = 'Responses failed: ' . implode(',', $failedIds);
            $workspace->logWorkspaceSync(\Yii::$app->user->getId(), Workspace::SYNC_PARTIALLY_OK, $sync_error);
        } else {
            $workspace->logWorkspaceSync(\Yii::$app->user->getId(), Workspace::SYNC_FAILED);
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
