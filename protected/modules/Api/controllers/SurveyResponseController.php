<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\surveyResponse\Create;
use prime\modules\Api\controllers\surveyResponse\View;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SurveyResponseController extends Controller
{
    public function actions(): array
    {
        return [
            'create' => Create::class,
            'view' => View::class,
        ];
    }
}
