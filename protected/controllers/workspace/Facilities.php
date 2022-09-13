<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use Collecthor\DataInterfaces\VariableInterface;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\models\search\FacilitySearch;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;
use function iter\filter;
use function iter\toArray;

class Facilities extends Action
{
    public function run(
        Request $request,
        BreadcrumbService $breadcrumbService,
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspaceId = new WorkspaceId($id);
        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $variableSet = $surveyRepository->retrieveVariableSet($projectRepository->retrieveAdminSurveyId($projectId), $projectRepository->retrieveDataSurveyId($projectId));
        $facilitySearch = new FacilitySearch();
        $facilitySearch->load($request->queryParams);

        $variables = toArray(filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInFacilityList') !== null, $variableSet->getVariables()));
        usort($variables, fn (VariableInterface $a, VariableInterface $b) => $a->getRawConfigurationValue('showInFacilityList') <=> $b->getRawConfigurationValue('showInFacilityList'));

        $this->controller->view->breadcrumbCollection->add(...toArray($breadcrumbService->retrieveForWorkspace($workspaceId)->getIterator()));
        return $this->controller->render('facilities', [
            'facilitySearch' => $facilitySearch,
            'variables' => $variables,
        ]);
    }
}
