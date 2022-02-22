<?php

declare(strict_types=1);

namespace prime\controllers\element;

use prime\models\ar\Page;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\values\ProjectId;
use prime\values\SurveyId;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class PickQuestion extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        int $page_id
    ) {
        $page = Page::findOne(['id' => $page_id]);
        if (!isset($page)) {
            throw new NotFoundHttpException();
        }

        $project = $projectRepository->retrieveForRead(new ProjectId($page->project_id));
        if (isset($project->data_survey_id)) {
            $dataVariables = $surveyRepository->retrieveForDashboarding(new SurveyId($project->data_survey_id));
        }

        $adminVariables = $surveyRepository->retrieveForDashboarding(new SurveyId($project->admin_survey_id));


        return $this->controller->render('pick-question', [
            'dataVariables' => $dataVariables ?? [],
            'adminVariables' => $adminVariables,
            'page' => $page
        ]);
    }
}
