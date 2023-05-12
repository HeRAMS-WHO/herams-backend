<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\facility\AdminResponses;
use prime\controllers\facility\Create;
use prime\controllers\facility\Index;
use prime\controllers\facility\Responses;
use prime\controllers\facility\Update;
use prime\controllers\facility\UpdateSituation;
use prime\controllers\facility\View;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\values\SurveyResponseId;
use herams\common\models\SurveyResponse;
use prime\controllers\facility\CreateAdminSituation;
use prime\components\BreadcrumbService;

final class FacilityController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;
    public function __construct(
        $id,
        $module,
        private ProjectRepository $projectRepository,
        private FacilityRepository $facilityRepository,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }
    public function actions(): array
    {
        return [
            'admin-responses' => AdminResponses::class,
            'create' => Create::class,
            'index' => Index::class,
            'responses' => Responses::class,
            'update' => Update::class,
            'view' => View::class,
            'update-situation' => UpdateSituation::class,
            'create-admin-situation' => CreateAdminSituation::class,
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }
    public function actionEditSituation(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        SurveyResponseRepository $surveyResponseRepository,
        int $pid,   //parentID
        int $cid    //childId

    ) {
        $facilityId = new FacilityId($pid);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);
        
        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($cid));
        return $this->render('situation/edit', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'facilityId' => $facilityId,
            'surveyId' => $surveyId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $survey,
            'response' => $response,
            'surveyResponseId' => new SurveyResponseId($cid),
            'cid' => $cid
        ]);
    }    
    public function actionViewSituation(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        SurveyResponseRepository $surveyResponseRepository,
        int $pid,   //parentID
        int $cid    //childId

    ) {
        $facilityId = new FacilityId($pid);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);

        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($cid));

        return $this->render('situation/view', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'facilityId' => $facilityId,
            'surveyId' => $surveyId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $survey,
            'response' => $response,
            'surveyResponseId' => new SurveyResponseId($cid),
            'cid' => $cid
        ]);
    }   
    
    public function actionDeleteSituation(
        int $pid,   //parentID  -facility id
        int $cid    //childId - response id
    ) {
        $model = SurveyResponse::findOne($cid);
        $model->status = 'Deleted';
        $model->update();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    public function actionEditAdminSituation(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        SurveyResponseRepository $surveyResponseRepository,
        BreadcrumbService $breadcrumbService,
        \prime\components\View $view,
        int $pid,   //parentID
        int $cid    //childId

    ) {
        $facilityId = new FacilityId($pid);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $view->getBreadcrumbCollection()->mergeWith($breadcrumbService->retrieveForFacility($facilityId));
        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);

        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($cid));

        return $this->render('admin-situation/edit', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'facilityId' => $facilityId,
            'surveyId' => $surveyId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $surveyRepository->retrieveForSurveyJs($response->getSurveyId()),
            'response' => $response,
            'surveyResponseId' => new SurveyResponseId($cid),
            'cid' => $cid
        ]);
    }    
    public function actionViewAdminSituation(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        SurveyResponseRepository $surveyResponseRepository,
        int $pid,   //parentID
        int $cid    //childId

    ) {
        $facilityId = new FacilityId($pid);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);

        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($cid));

        return $this->render('admin-situation/view', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'facilityId' => $facilityId,
            'surveyId' => $surveyId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $surveyRepository->retrieveForSurveyJs($response->getSurveyId()),
            'response' => $response,
            'surveyResponseId' => new SurveyResponseId($cid),
            'cid' => $cid
        ]);
    }

    public function render($view, $params = [])
    {
        if (! isset($params['tabMenuModel']) && $this->request->getQueryParam('id')) {
            $facilityId = new FacilityId((int) $this->request->getQueryParam('id'));
            $params['tabMenuModel'] = $this->facilityRepository->retrieveForTabMenu($facilityId);
        }
        return parent::render($view, $params);
    }
    
}

