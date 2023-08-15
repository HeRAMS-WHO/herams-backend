<?php

declare(strict_types=1);

namespace herams\api\controllers\surveyResponse;

use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\values\SurveyResponseId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        SurveyResponseRepository $surveyResponseRepository,
        int $id
    ) {
        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($id));
        print_r($response);
        exit;
    }
}
