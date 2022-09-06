<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\facility;

use prime\helpers\ModelHydrator;
use prime\helpers\ModelValidator;
use prime\modules\Api\models\NewFacility;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

class ValidateNew extends Action
{
    public function run(
        Request $request,
        ModelHydrator $modelHydrator,
        ModelValidator $modelValidator,
        Response $response
    ): Response {
        $facility = new NewFacility();

        $modelHydrator->hydrateFromJsonDictionary($facility, $request->bodyParams);
        $modelValidator->validateModel($facility);
        return $modelValidator->renderForValidationEndpoint($facility, $response);
    }
}
