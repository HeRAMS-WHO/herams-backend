<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use herams\common\values\ProjectId;
use prime\components\Controller;
use prime\repositories\FormRepository;
use prime\widgets\survey\SurveyFormWidget;
use yii\base\Action;
use yii\web\Response;

class Create extends Action
{
    public function run(
        FormRepository $formRepository,
        int $project_id
    ) {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $survey = new SurveyFormWidget();
        $survey->withForm($formRepository->getCreateWorkspaceForm(new ProjectId($project_id)))->setConfig();
        $settings = $survey->getConfig();

        return [
            'settings' => $settings,
        ];
    }
}
