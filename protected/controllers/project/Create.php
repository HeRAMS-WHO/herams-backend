<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use prime\components\Controller;
use prime\repositories\FormRepository;
use prime\widgets\survey\SurveyFormWidget;
use yii\base\Action;
use yii\web\Response;

class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        FormRepository $formRepository,
    ) {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $accessCheck->requireGlobalPermission(PermissionOld::PERMISSION_CREATE_PROJECT);

        $survey = new SurveyFormWidget();
        $survey->withForm($formRepository->getCreateProjectForm())->setConfig();
        $settings = $survey->getConfig();

        return [
            'settings' => $settings,
        ];
    }
}
