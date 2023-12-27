<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use prime\models\forms\survey\CreateForm;
use prime\widgets\surveyJs\Creator2;
use yii\base\Action;
use yii\web\Response;

class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
    ): array {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new CreateForm();
        $accessCheck->requireGlobalPermission(PermissionOld::PERMISSION_CREATE_SURVEY);

        $creator = new Creator2();
        $creator->setConfig();
        $settings = $creator->getConfig();

        return [
            'settings' => $settings,
        ];
    }
}
