<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\api\domain\project\NewProject;
use herams\common\domain\project\ProjectRepository;
use herams\common\enums\ProjectVisibility;
use herams\common\helpers\CommonFieldsInTables;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use herams\common\values\Visibility;
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
        $data = $request->bodyParams['data'];
        $data = [...$data, ...CommonFieldsInTables::forCreatingHydratation()];
        $model = new NewProject();
        $modelHydrator->hydrateFromJsonDictionary($model, $data);

        // Our model is now hydrated, we should validate it.
        if (! $modelValidator->validateModel($model)) {
            return $modelValidator->renderValidationErrors($model, $response);
        }
        $visibility = ProjectVisibility::getValueFromLabel($data['projectvisibility']) ?? 'public';
        $model->visibility = new Visibility($visibility);
        $id = $projectRepository->create($model);
        $response->setStatusCode(204);
        $response->headers->add('Location', Url::to([
            '/api/project/view',
            'id' => $id,
        ]));

        return $response;
    }
}
