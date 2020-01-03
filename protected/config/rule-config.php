<?php
declare(strict_types=1);

use prime\models\permissions\Permission;
use SamIT\abac\rules\ImpliedPermission;

return [
    new \prime\rules\AdminRule(),
    new \prime\rules\WorkspaceRule(),
    new \prime\rules\AdminImpliesRule(),
    new \prime\rules\WorkspaceDataRule(),
    new \prime\rules\GrantRule(),
    new ImpliedPermission(Permission::PERMISSION_ADMIN, [
        Permission::PERMISSION_SHARE,
        Permission::PERMISSION_WRITE
    ])
];