<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use prime\actions\FrontendAction;
use prime\components\BreadcrumbService;
use function iter\filter;

class Responses extends FrontendAction
{
    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        BreadcrumbService $breadcrumbService,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForTabMenu($facilityId);

        $this->controller->view->breadcrumbCollection->mergeWith($breadcrumbService->retrieveForFacility($facilityId));

        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
        $variableSet = $surveyRepository->retrieveSimpleVariableSet($surveyId);

        $updateSituationUrl = [
            'update-situation',
            'id' => $facility->getId(),

        ];

        return $this->controller->render(
            'responses',
            [
                'facility' => $facility,
                'updateSituationUrl' => $updateSituationUrl,
                'facilityId' => $facilityId,
                'variables' => filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInResponseList') === true, $variableSet->getVariables()),
            ]
        );
    }
}
