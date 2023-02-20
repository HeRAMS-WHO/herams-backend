<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\api\domain\project\NewProject;
use herams\api\domain\project\UpdateProject;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use herams\common\values\ProjectId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

final class Validate extends Action
{
    public function run(
        Request $request,
        ModelHydrator $modelHydrator,
        ModelValidator $modelValidator,
        Response $response,
        int $id = null
    ): Response {
        if (! isset($id)) {
            $model = new NewProject();
        } else {
            $model = new UpdateProject(new ProjectId($id));
        }
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams['data']);
        return $modelValidator->validateAndRenderForValidationEndpoint($model, $response);
    }
}
