<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use prime\helpers\ModelHydrator;
use prime\models\search\SurveySearch;
use prime\repositories\SurveyRepository;
use yii\base\Action;
use yii\web\Request;

class Index extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        Request $request,
        SurveyRepository $repository
    ) {
        $this->controller->view->autoAddTitleToBreadcrumbs = false;

        $surveySearch = new SurveySearch();
        $modelHydrator->hydrateFromRequestQuery($surveySearch, $request);

        return $this->controller->render(
            'index',
            [
                'surveyProvider' => $repository->search($surveySearch),
                'surveySearchModel' => $surveySearch,
            ]
        );
    }
}
