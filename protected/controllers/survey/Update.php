<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use herams\common\values\SurveyId;
use prime\actions\FrontendAction;
use prime\models\forms\survey\CreateForm;
use prime\widgets\surveyJs\Creator2;
use yii\web\Response;

final class Update extends FrontendAction
{
    public function run(
        int $id,
    ) {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new CreateForm();

        $creator = new Creator2();
        $creator->setConfig(new SurveyId($id));
        $settings = $creator->getConfig();

        return [
            'settings' => $settings,
        ];
    }
}
