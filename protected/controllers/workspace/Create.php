<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\helpers\ConfigurationProvider;
use prime\repositories\SurveyRepository;
use prime\values\ProjectId;
use yii\base\Action;

class Create extends Action
{
    public function run(
        ConfigurationProvider $configurationProvider,
        SurveyRepository $surveyRepository,
        int $project_id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        return $this->controller->render('create', [
            'projectId' => new ProjectId($project_id),
            'survey' => $surveyRepository->retrieveForSurveyJs($configurationProvider->getCreateWorkspaceSurveyId()),
        ]);
    }
}
