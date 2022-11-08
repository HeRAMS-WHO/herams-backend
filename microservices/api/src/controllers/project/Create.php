<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\api\models\NewProject;
use herams\common\domain\project\ProjectRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;

final class Create extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        ModelValidator $modelValidator,
        ProjectRepository $projectRepository,
        Request $request,
        Response $response,
    ) {
        $model = new NewProject();

        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams['data']);

        // Our model is now hydrated, we should validate it.
        if (! $modelValidator->validateModel($model)) {
            return $modelValidator->renderValidationErrors($model, $response);
        }

        $id = $projectRepository->create($model);
        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to([
            '/api/project/view',
            'id' => $id,
        ]));

        return $response;
    }
}
