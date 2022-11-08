<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\surveyResponse\Create;
use herams\api\controllers\surveyResponse\View;

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
