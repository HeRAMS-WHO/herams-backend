<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Survey;
use prime\models\survey\SurveyForCreate;
use yii\base\Action;

class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
    ): string {
        $model = new SurveyForCreate();
        $accessCheck->checkPermission(new Survey(), Permission::PERMISSION_CREATE);

        return $this->controller->render(
            'createAndUpdate',
            [
                'model' => $model,
            ]
        );
    }
}
