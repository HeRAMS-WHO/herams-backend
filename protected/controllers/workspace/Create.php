<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\helpers\ConfigurationProvider;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\Request;

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
