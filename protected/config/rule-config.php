<?php
declare(strict_types=1);

use prime\models\permissions\Permission;
use prime\rules\AdminRule;
use prime\rules\DeleteWorkspaceRule;
use prime\rules\GrantRule;
use prime\rules\ProjectImpliesWorkspace;
use prime\rules\ProjectReadRule;
use prime\rules\WorkspaceDataRule;
use prime\rules\WorkspaceRule;
use SamIT\abac\rules\ImpliedPermission;

return [
    new AdminRule(),
    new GrantRule(),
    new ImpliedPermission(Permission::PERMISSION_ADMIN, [
        Permission::PERMISSION_SHARE,
        Permission::PERMISSION_WRITE,
        Permission::PERMISSION_DELETE,
        Permission::PERMISSION_EXPORT,
        Permission::PERMISSION_LIMESURVEY,
        Permission::PERMISSION_MANAGE_WORKSPACES
    ]),
    new ImpliedPermission(Permission::PERMISSION_WRITE, [
        Permission::PERMISSION_READ,
    ]),
    new ProjectReadRule(),
    new ProjectImpliesWorkspace(),
    new DeleteWorkspaceRule(),
];