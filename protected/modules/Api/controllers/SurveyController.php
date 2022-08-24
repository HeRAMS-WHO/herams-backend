<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\models\search\SurveySearch;
use prime\repositories\SurveyRepository;
use yii\web\Controller;

class SurveyController extends Controller
{

    public function actionIndex(SurveyRepository $surveyRepository)
    {
        return $surveyRepository->search(new SurveySearch())->getModels();
    }
}
