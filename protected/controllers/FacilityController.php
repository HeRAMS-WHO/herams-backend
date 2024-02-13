<?php

declare(strict_types=1);

namespace prime\controllers;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\models\SurveyResponse;
use herams\common\values\FacilityId;
use herams\common\values\SurveyResponseId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\controllers\facility\AdminResponses;
use prime\controllers\facility\Create;
use prime\controllers\facility\CreateAdminSituation;
use prime\controllers\facility\Index;
use prime\controllers\facility\Responses;
use prime\controllers\facility\Update;
use prime\controllers\facility\UpdateSituation;
use prime\controllers\facility\View;
use prime\widgets\survey\Survey;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $facilityId = new FacilityId($pid);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);
        $surveyResponseId = new SurveyResponseId($cid);
        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($cid));
        $surveyJS = new Survey();
        $surveyConfig = $survey->getConfig();
        $surveyJS->withConfig($surveyConfig)
            ->withDataRoute([
                '/api/facility/view-situation',
                'id' => $cid,
            ], ['data'])
            ->withExtraData([
                'surveyResponseId' => $surveyResponseId,
                'facilityId' => $facilityId,
                'response_type' => 'situation',
                'response_id' => $cid, //respose id for validation
            ])
            ->withSubmitRoute([
                'edit-situation',
                'id' => $facilityId,
            ])
            ->withProjectId($projectId)
            ->withSubmitRoute([
                'api/facility/save-situation',
                'id' => $cid,
            ])
            ->withRedirectRoute([
                'facility/responses',
                'id' => $facilityId,
            ])
            ->withServerValidationRoute([
                'api/facility/validate-situation',
                'id' => $facilityId,

            ])->setSurveySettings();
        $surveySettings = $surveyJS->getSurveySettings();

        return [
            'settings' => $surveySettings,
        ];
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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $facilityId = new FacilityId($pid);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);

        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($cid));
        $surveyJS = new Survey();
        $surveyJS->withConfig($survey->getConfig())
            ->withDataRoute([
                '/api/facility/view-situation',
                'id' => $cid,
            ], ['data'])
            ->withProjectId($projectId)
            ->inDisplayMode()->setSurveySettings();

        $surveySettings = $surveyJS->getSurveySettings();
        return [
            'settings' => $surveySettings,
        ];
    }

    public function actionDeleteSituation(
        int $pid,   //parentID  -facility id
        int $cid,    //childId - response id
        SurveyResponseRepository $surveyResponseRepository
    ) {
        $model = SurveyResponse::findOne($cid);
        $model->status = 'Deleted';
        $model->update();
        $surveyResponseId = new SurveyResponseId($cid);
        $surveyResponseRepository->propagateSurveysResponses($surveyResponseId);
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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $facilityId = new FacilityId($pid);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);
        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);
        $surveyJS = new Survey();
        $surveyConfig = $survey->getConfig();
        $surveyResponseId = new SurveyResponseId($cid);
        $surveyJS->withConfig($surveyConfig)
            ->withDataRoute([
                '/api/facility/view-situation',
                'id' => $cid,
            ], ['data'])
            ->withExtraData([
                'surveyResponseId' => $surveyResponseId,
                'facilityId' => $facilityId,
                'response_type' => 'admin',
                'response_id' => $cid, //respose id for validation
            ])
            ->withProjectId($projectId)
            ->withSubmitRoute([
                'api/facility/save-situation',
                'id' => $cid,
            ])
            ->withRedirectRoute([
                'facility/admin-responses',
                'id' => $facilityId,
            ])
            ->withServerValidationRoute([
                'api/facility/validate-situation',
                'id' => $facilityId,

            ])->setSurveySettings();
        $surveySettings = $surveyJS->getSurveySettings();
        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($cid));

        return [
            'settings' => $surveySettings,
        ];
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
        Yii::$app->response->format = Response::FORMAT_JSON;

        $facilityId = new FacilityId($pid);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);

        $response = $surveyResponseRepository->retrieve(new SurveyResponseId($cid));
        $surveyConfig = $survey->getConfig();
        $surveyJS = new Survey();
        $surveyJS->withConfig($survey->getConfig())
            ->withDataRoute([
                '/api/facility/view-situation',
                'id' => $cid,
            ], ['data'])
            ->withProjectId($projectId)
            ->inDisplayMode()->setSurveySettings();

        $surveySettings = $surveyJS->getSurveySettings();
        return [
            'settings' => $surveySettings,
        ];
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
