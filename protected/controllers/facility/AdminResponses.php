<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use prime\actions\FrontendAction;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use yii\helpers\Url;
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
        BreadcrumbService $breadcrumbService,
        \prime\components\View $view,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForTabMenu($facilityId);

        $view->getBreadcrumbCollection()->mergeWith($breadcrumbService->retrieveForFacility($facilityId));
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $variableSet = $surveyRepository->retrieveSimpleVariableSet($surveyId);

        return $this->render(
            'admin-responses',
            [
                'facility' => $facility,
                'dataRoute' => Url::to(['/api/facility/admin-responses', 'id' => $facilityId, 'language' => \Yii::$app->language]),
                'variables' => filter(static fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInResponseList') === true, $variableSet->getVariables()),
            ]
        );
    }
}
