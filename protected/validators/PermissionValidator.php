<?php
declare(strict_types=1);

namespace prime\validators;

use prime\models\ar\Permission;
use yii\validators\Validator;

class PermissionValidator extends Validator
{

    public string $permission = Permission::PERMISSION_ADMIN;

    public string $modelClass ;

    protected function validateValue($value)
    {
        parent::validateValue($value);
    }


}
