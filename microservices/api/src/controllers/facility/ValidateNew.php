<?php

declare(strict_types=1);

namespace herams\api\controllers\facility;

use herams\common\domain\facility\NewFacility;
use herams\common\helpers\CommonFieldsInTables;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
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
        $data = [
            ...$request->bodyParams,
            ...CommonFieldsInTables::forCreatingHydratation(),
        ];
        $data['situationData'] = [];
        $data['adminData'] = $data['data'];
        $modelHydrator->hydrateFromJsonDictionary($facility, $data);
        return $modelValidator->validateAndRenderForValidationEndpoint($facility, $response);
    }
}
