<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\workspace;

use prime\helpers\ModelHydrator;
use prime\modules\Api\models\UpdateWorkspace;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

final class Update extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        Request $request,
        WorkspaceRepository $workspaceRepository,
        Response $response,
        int $id
    ) {
        $model = new UpdateWorkspace(new WorkspaceId($id));

        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams['data']);
        \Yii::debug($request->bodyParams['data']);
        if (! $model->validate()) {
            $response->setStatusCode(422);
            return $model->errors;
        }

        $workspaceRepository->update($model);

        $response->setStatusCode(200);
        return $response;
    }
}
