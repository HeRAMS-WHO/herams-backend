<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\workspace;

use prime\components\LimesurveyDataProvider;
use prime\helpers\LimesurveyDataLoader;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use yii\base\Action;
use yii\web\Request;

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

        $new = $updated = $unchanged = $failed = 0;
        $start = microtime(true);
        $ids = [];
        foreach ($limesurveyDataProvider->refreshResponsesByToken($workspace->project->base_survey_eid, $workspace->getAttribute('token')) as $response) {
            $ids[] = $response->getId();
            $key = [
                'id' => $response->getId(),
                'survey_id' => $workspace->project->base_survey_eid,
                'workspace_id' => $workspace->id
            ];

            $dataResponse = Response::findOne($key) ?? new Response($key);
            $loader->loadData($response->getData(), $workspace, $dataResponse);
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
