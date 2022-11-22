<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use herams\common\values\SurveyId;
use prime\actions\FrontendAction;

final class Update extends FrontendAction
{
    public function run(
        int $id,
    ) {
        return $this->render(
            'update',
            [
                'surveyId' => new SurveyId($id),
            ]
        );
    }
}
