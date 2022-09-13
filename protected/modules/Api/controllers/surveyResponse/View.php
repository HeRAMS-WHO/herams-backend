<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\surveyResponse;

use prime\repositories\SurveyResponseRepository;
use prime\values\SurveyResponseId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        SurveyResponseRepository $surveyResponseRepository,
        int $id
    ) {
        return $surveyResponseRepository->retrieve(new SurveyResponseId($id));
    }
}
