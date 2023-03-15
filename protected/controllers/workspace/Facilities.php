<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use yii\base\Action;
use yii\web\Request;
use function iter\filter;
use function iter\toArray;

class Facilities extends Action
{
    public function run(
        Request $request,
        BreadcrumbService $breadcrumbService,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspaceId = new WorkspaceId($id);
        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $variableSet = $surveyRepository->retrieveVariableSet($projectRepository->retrieveAdminSurveyId($projectId), $projectRepository->retrieveDataSurveyId($projectId));

        $variables = toArray(filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInFacilityList') !== null, $variableSet->getVariables()));
        usort($variables, fn (VariableInterface $a, VariableInterface $b) => $a->getRawConfigurationValue('showInFacilityList') <=> $b->getRawConfigurationValue('showInFacilityList'));

        $this->controller->view->breadcrumbCollection->add(...toArray($breadcrumbService->retrieveForWorkspace($workspaceId)->getIterator()));
        return $this->controller->render('facilities', [
            'variables' => $variables,
        ]);
    }
}
