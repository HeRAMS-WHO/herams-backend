<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\helpers\ConfigurationProvider;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\facility\CreateForm as CreateModel;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\base\InvalidArgumentException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Request;
use yii\web\Response;

use function iter\toArray;
use function PHPUnit\Framework\assertInstanceOf;

class Create extends Action
{
    public function __construct(
        $id,
        $controller,
        private WorkspaceRepository $workspaceRepository,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
    }

    public function run(
        AccessCheckInterface $accessCheck,
        SurveyRepository $surveyRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        BreadcrumbService $breadcrumbService,
        int $workspaceId
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspaceId = new WorkspaceId($workspaceId);
        $accessCheck->requirePermission($workspaceId, Permission::PERMISSION_CREATE_FACILITY);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $this->controller->view->breadcrumbCollection->add(...toArray($breadcrumbService->retrieveForWorkspace($workspaceId)->getIterator()));
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);

        return $this->controller->render('create', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'survey' => $surveyRepository->retrieveForSurveyJs($surveyId),
        ]);
    }
}
