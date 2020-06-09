<?php
declare(strict_types=1);

namespace prime\models\permissions;


use prime\models\ar\Permission;
use SamIT\abac\interfaces\Authorizable;
use SamIT\Yii2\abac\AccessChecker;
use yii\db\ActiveQuery;

class GlobalPermission implements Authorizable
{

    public function getPermissions(): ActiveQuery
    {
        return Permission::find()
            ->andWhere([
                'target_id' => AccessChecker::GLOBAL,
                'target' => AccessChecker::BUILTIN
            ]);
    }

    public function getId(): string
    {
        return AccessChecker::GLOBAL;
    }

    public function getAuthName(): string
    {
        return AccessChecker::BUILTIN;
    }
}