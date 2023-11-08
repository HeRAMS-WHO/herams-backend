<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\workspace;

use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\helpers\LimesurveyDataLoader;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;
use function iter\toArrayWithKeys;

class Refresh extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        LimesurveyDataProvider $limesurveyDataProvider,
        LimesurveyDataLoader $loader,
        int $id
    ) {
        $workspace = Workspace::findOne(['id' => $id]);

        $accessCheck->requirePermission($workspace, Permission::PERMISSION_ADMIN);

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
        return [
            'new' => $new,
            'updated' => $updated,
            'deleted' => $deleted,
            'unchanged' => $unchanged,
            'failed' => $failed,
            'time' => number_format(microtime(true) - $start, 2) . 's'
        ];
    }
}
