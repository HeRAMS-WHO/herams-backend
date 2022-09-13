<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\workspace;

use prime\helpers\ModelHydrator;
use prime\helpers\ModelValidator;
use prime\modules\Api\models\NewWorkspace;
use prime\repositories\WorkspaceRepository;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;

class Create extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        ModelValidator $modelValidator,
        WorkspaceRepository $workspaceRepository,
        Request $request,
        \yii\web\Response $response,
    ) {
        $model = new NewWorkspace();

        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams['data']);

        // Our model is now hydrated, we should validate it.
        if (! $modelValidator->validateModel($model)) {
            return $modelValidator->renderValidationErrors($model, $response);
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
