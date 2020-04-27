<?php
declare(strict_types=1);

use prime\models\permissions\Permission;
use prime\rules\AdminRule;
use prime\rules\AdminShareRule;
use prime\rules\DashboardRule;
use prime\rules\GrantRule;
use prime\rules\ManageWorkspaceRule;
use prime\rules\ProjectImpliesWorkspace;
use prime\rules\ProjectReadRule;
use prime\rules\ProjectSummaryRule;
use prime\rules\PublicProjectRule;
use prime\rules\RevokeRule;
use prime\rules\SuperShareRule;
use SamIT\abac\rules\ImpliedPermission;

return [
//    new AdminRule(),
    new GrantRule(),
    new ProjectSummaryRule(),
    new RevokeRule(),
    new DashboardRule(),
    new SuperShareRule(),
    new AdminShareRule(),
    new ImpliedPermission(Permission::PERMISSION_ADMIN, [
        Permission::PERMISSION_SHARE,
        Permission::PERMISSION_WRITE,
        Permission::PERMISSION_DELETE,
        Permission::PERMISSION_EXPORT,
        Permission::PERMISSION_LIMESURVEY,
        Permission::PERMISSION_MANAGE_WORKSPACES,
        Permission::PERMISSION_MANAGE_DASHBOARD
    ]),
    new ImpliedPermission(Permission::PERMISSION_WRITE, [
        Permission::PERMISSION_READ,
    ]),
    new ProjectReadRule(),
    new PublicProjectRule(),
    new ProjectImpliesWorkspace(),
    new ManageWorkspaceRule(),
];