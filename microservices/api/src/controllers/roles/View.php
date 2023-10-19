<?php

declare(strict_types=1);

namespace herams\api\controllers\roles;

use herams\common\models\Role;
use yii\base\Action;

class View extends Action
{
    public function run(int $id): array
    {
        if (! $id) {
            return [];
        }
        return Role::find()
            ->where([
                'id' => $id,
            ])
            ->with([
                'projectInfo' => fn ($query) => $query->select(['id', 'primary_language', 'i18n']),
                'updaterUserInfo' => fn ($query) => $query->select(['id', 'name']),
                'creatorUserInfo' => fn ($query) => $query->select(['id', 'name']),
                'rolePermissions' => fn ($query) => $query->select(['role_id', 'permission_code']),
            ])
            ->asArray()
            ->one();
    }
}
