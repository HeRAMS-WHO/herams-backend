<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use Collecthor\DataInterfaces\VariableInterface;
use prime\actions\FrontendAction;
use prime\components\Controller;
use prime\objects\Breadcrumb;
use prime\objects\BreadcrumbCollection;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\SurveyResponseRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\FacilityId;
use function iter\filter;

class AdminResponses extends FrontendAction
{
    public function __construct(
        string $id,
        Controller $controller,
        private FacilityRepository $facilityRepository,
    ) {
        parent::__construct($id, $controller, []);
    }

    public function run(
        FacilityRepository $facilityRepository,
        SurveyRepository $surveyRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyResponseRepository $surveyResponseRepository,
        ProjectRepository $projectRepository,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForTabMenu($facilityId);

        $this->getBreadcrumbCollection()->add($facilityRepository->retrieveForBreadcrumb($facilityId));
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $variableSet = $surveyRepository->retrieveSimpleVariableSet($surveyId);
        $dataProvider = $surveyResponseRepository->searchAdminInFacility($facilityId);

        return $this->render(
            'admin-responses',
            [
                'responseProvider' => $dataProvider,
                'facility' => $facility,
                'variables' => filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInResponseList') === true, $variableSet->getVariables()),
            ]
        );
    }

    protected function configureBreadcrumbs(BreadcrumbCollection $breadcrumbCollection): void
    {
        $breadcrumbCollection->add(new Breadcrumb(\Yii::t('app', 'Administrative responses')));
    }
}
