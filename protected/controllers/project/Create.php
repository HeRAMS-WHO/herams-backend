<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Permission;
use prime\components\Controller;
use prime\components\NotificationService;
use prime\helpers\ConfigurationProvider;
use yii\base\Action;
use yii\web\Request;

class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        ModelHydrator $modelHydrator,
        ConfigurationProvider $configurationProvider,
        SurveyRepository $surveyRepository,
        NotificationService $notificationService,
        ProjectRepository $projectRepository,
        Request $request
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $accessCheck->requireGlobalPermission(Permission::PERMISSION_CREATE_PROJECT);

        return $this->controller->render('create-surveyjs', [
            'survey' => $surveyRepository->retrieveForSurveyJs($configurationProvider->getCreateProjectSurveyId()),
        ]);
    }
}
