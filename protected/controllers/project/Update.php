<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\values\ProjectId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\helpers\ConfigurationProvider;
use yii\base\Action;
use function iter\toArray;

class Update extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        ConfigurationProvider $configurationProvider,
        BreadcrumbService $breadcrumbService,
        SurveyRepository $surveyRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $projectId = new ProjectId($id);

        $this->controller->view->breadcrumbCollection->add(
            ...toArray($breadcrumbService->retrieveForProject($projectId)->getIterator())
        );

        $survey = $surveyRepository->retrieveForSurveyJs($configurationProvider->getUpdateProjectSurveyId());
        $project = $projectRepository->retrieveForRead($projectId);
        return $this->controller->render('update', [
            'project' => $project,
            'projectId' => $projectId,
            'survey' => $survey,
        ]);
    }
}
