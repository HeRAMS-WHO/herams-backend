<?php
declare(strict_types=1);

namespace prime\controllers\response;

use prime\components\Controller;
use prime\repositories\SurveyRepository;
use prime\repositories\SurveyResponseRepository;
use prime\values\FacilityId;
use prime\values\SurveyResponseId;
use yii\base\Action;

class View extends Action
{


    public function run(
        SurveyResponseRepository $surveyResponseRepository,
        SurveyRepository $surveyRepository,
        int $id
    ): string
    {
        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($id));

        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;


        return $this->controller->render('view', [
            'survey' => $surveyRepository->retrieveForSurveyJs($response->getSurveyId()),
            'projectId' => $response->getProjectId(),
            'id' => new SurveyResponseId($id)
        ]);


    }
}
