<?php

declare(strict_types=1);

namespace herams\api\controllers\roles;

use herams\common\models\Role;
use yii\base\Action;

class Index extends Action
{
    public function run(
    ) {
        return Role::find()
            ->with([
                'projectInfo',
                'updaterUserInfo' => fn($query) => $query->select(['id', 'name']),
                'creatorUserInfo'=> fn($query) => $query->select(['id', 'name']),
            ])
            ->asArray()
            ->all();
    }
}
