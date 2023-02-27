<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use herams\api\domain\workspace\NewWorkspace;
use herams\api\domain\workspace\UpdateWorkspace;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use herams\common\values\WorkspaceId;
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
            $model = new NewWorkspace();
        } else {
            $model = new UpdateWorkspace(new WorkspaceId($id));
        }
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams['data']);
        return $modelValidator->validateAndRenderForValidationEndpoint($model, $response);
    }
}
