<?php

declare(strict_types=1);

namespace herams\common\models;

use SamIT\abac\interfaces\Authorizable;
use SamIT\Yii2\abac\AccessChecker;
use yii\db\ActiveQuery;

/**
 * @codeCoverageIgnore
 */
class GlobalPermission implements Authorizable
{
    public function getId(): string
    {
        return AccessChecker::GLOBAL;
    }

    public function getAuthName(): string
    {
        return AccessChecker::BUILTIN;
    }

    public function getPermissions(): ActiveQuery
    {
        return PermissionOld::find()
            ->andWhere([
                'target_id' => AccessChecker::GLOBAL,
                'target' => AccessChecker::BUILTIN,
            ]);
    }
}
