<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use prime\repositories\SurveyRepository;
use prime\values\SurveyId;
use yii\base\Action;

class Update extends Action
{
    public function run(
        SurveyRepository $repository,
        int $id,
    ) {
        $model = $repository->retrieveForUpdate(new SurveyId($id));

        return $this->controller->render(
            'createAndUpdate',
            [
                'model' => $model,
            ]
        );
    }
}
