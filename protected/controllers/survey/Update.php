<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use herams\common\domain\survey\SurveyRepository;
use herams\common\values\SurveyId;
use prime\components\Controller;
use yii\base\Action;

class Update extends Action
{
    public function run(
        SurveyRepository $repository,
        int $id,
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN;
        $model = $repository->retrieveForUpdate(new SurveyId($id));

        return $this->controller->render(
            'createAndUpdate',
            [
                'model' => $model,
            ]
        );
    }
}
