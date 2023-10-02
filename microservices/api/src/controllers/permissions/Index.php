<?php

declare(strict_types=1);

namespace herams\api\controllers\permissions;

use herams\common\models\Permission;
use yii\base\Action;

class Index extends Action
{
    public function run(
    ) {
        return Permission::find()
            ->asArray()
            ->all();
    }
}
