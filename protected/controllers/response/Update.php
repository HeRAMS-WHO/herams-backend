<?php

declare(strict_types=1);

namespace prime\controllers\response;

use prime\repositories\ResponseForLimesurveyRepository;
use prime\values\ResponseId;
use yii\base\Action;

class Update extends Action
{
    public function run(
        ResponseForLimesurveyRepository $responseRepository,
        int $id
    ) {
        $responseId = new ResponseId($id);
        $response = $responseRepository->retrieveForSurvey($responseId);
        if ($response->usesLimeSurvey()) {
            return $this->controller->render('update-limesurvey', ['model' => $response]);
        }
    }
}
