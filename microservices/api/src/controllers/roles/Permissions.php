<?php

declare(strict_types=1);

namespace herams\api\controllers\roles;

use herams\common\models\RolePermission;
use yii\base\Action;

class Permissions extends Action
{
    public function run(int $id): array
    {
        return RolePermission::find()
            ->where([
                'role_id' => $id,
            ])
            ->asArray()
            ->all();
    }
}
