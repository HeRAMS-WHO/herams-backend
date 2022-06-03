<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\workspace;

use prime\helpers\ModelHydrator;
use prime\modules\Api\models\NewWorkspace;
use prime\repositories\WorkspaceRepository;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\UnprocessableEntityHttpException;

class Create extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        WorkspaceRepository $workspaceRepository,
        Request $request,
        \yii\web\Response $response,
    ) {
        $model = new NewWorkspace();

        $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams);

        // Our model is now hydrated, we should validate it.
        if (! $model->validate()) {
            $response->setStatusCode(422);
            $response->data = [
                'errors' => $model->errors,
            ];
            return $response;
        }
        $id = $workspaceRepository->create($model);
        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to([
            '/api/workspace/view',
            'id' => $id,
        ]));

        return $response;
    }
}
