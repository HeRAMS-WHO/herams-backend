<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\helpers\ConfigurationProvider;
use yii\base\Action;
use function iter\toArray;

class Update extends Action
{
    public function run(
        ConfigurationProvider $configurationProvider,
        SurveyRepository $surveyRepository,
        WorkspaceRepository $workspaceRepository,
        BreadcrumbService $breadcrumbService,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspaceId = new WorkspaceId($id);
        $survey = $surveyRepository->retrieveForSurveyJs($configurationProvider->getUpdateWorkspaceSurveyId());
        $this->controller->view->breadcrumbCollection->add(...toArray($breadcrumbService->retrieveForWorkspace($workspaceId)->getIterator()));
        return $this->controller->render('update', [
            'survey' => $survey,
            'workspaceId' => $workspaceId,
            'tabMenuModel' => $workspaceRepository->retrieveForTabMenu($workspaceId),
        ]);
    }
}
