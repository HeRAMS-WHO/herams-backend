<?php

declare(strict_types=1);

namespace herams\common\domain\userRole;

use herams\common\models\UserRole;
use herams\common\queries\ActiveQuery;
use yii\base\InvalidArgumentException;

final class UserRoleRepository
{
    public function create(UserRole $userRole): void
    {
        $userRole->save();
    }

}
