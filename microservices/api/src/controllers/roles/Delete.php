<?php

declare(strict_types=1);

namespace herams\api\controllers\roles;

use herams\common\helpers\CommonFieldsInTables;
use herams\common\models\Role;
use herams\common\models\RolePermission;
use herams\common\models\UserRole;
use yii\base\Action;
use yii\helpers\BaseInflector;

class Delete extends Action
{
    public function run( int $id): bool  {
        UserRole::deleteAll(['role_id' => $id]);
        RolePermission::deleteAll(['role_id' => $id]);
        Role::deleteAll(['id' => $id]);
        return true;
    }
}
