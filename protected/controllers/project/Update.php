<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\domain\project\ProjectRepository;
use herams\common\values\ProjectId;
use prime\components\BreadcrumbService;
use prime\repositories\FormRepository;
use prime\widgets\survey\SurveyFormWidget;
use yii\base\Action;
use yii\web\Response;
use function iter\toArray;

class Update extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        BreadcrumbService $breadcrumbService,
        FormRepository $formRepository,
        int $id
    ) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $projectId = new ProjectId($id);
        $this->controller->view->breadcrumbCollection->add(
            ...toArray($breadcrumbService->retrieveForProject($projectId)->getIterator())
        );

        $project = $projectRepository->retrieveForRead($projectId);

        $survey = new SurveyFormWidget();
        $survey->withForm($formRepository->getUpdateProjectForm($projectId))->setConfig();

        $settings = $survey->getConfig();

        return [
            'settings' => $settings,
        ];
    }
}
