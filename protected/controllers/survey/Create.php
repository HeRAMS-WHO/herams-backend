<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Permission;
use prime\models\forms\survey\CreateForm;
use yii\base\Action;

class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
    ): string {
        $model = new CreateForm();
        $accessCheck->requireGlobalPermission(Permission::PERMISSION_CREATE_SURVEY);

        return $this->controller->render(
            'create',
        );
    }
}
