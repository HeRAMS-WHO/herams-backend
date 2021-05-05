<?php
declare(strict_types=1);

use prime\models\ar\Permission;
use prime\rules\AdminRule;
use prime\rules\AdminShareRule;
use prime\rules\CreateFacilityRule;
use prime\rules\DashboardRule;
use prime\rules\GrantRule;
use prime\rules\ManageWorkspaceRule;
use prime\rules\ProjectImplicitReadViaExplicitWorkspacePermission;
use prime\rules\ProjectImpliesWorkspace;
use prime\rules\ProjectSummaryRule;
use prime\rules\PublicProjectRule;
use prime\rules\RespondToAccessRequestRule;
use prime\rules\RevokeRule;
use prime\rules\SelfRule;
use prime\rules\SuperShareRule;
use SamIT\abac\rules\ImpliedPermission;

return [
    new AdminRule(),
    new SelfRule([Permission::PERMISSION_MANAGE_FAVORITES]),
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
        Permission::PERMISSION_SURVEY_DATA,
        Permission::PERMISSION_MANAGE_WORKSPACES,
        Permission::PERMISSION_MANAGE_DASHBOARD,
        Permission::PERMISSION_DELETE_ALL_WORKSPACES,
        Permission::PERMISSION_CREATE_FACILITY
    ]),
    new ImpliedPermission(Permission::PERMISSION_WRITE, [
        Permission::PERMISSION_READ,
    ]),
    new ImpliedPermission(Permission::PERMISSION_SURVEY_DATA, [
        Permission::PERMISSION_READ,
    ]),
    new ImpliedPermission(Permission::PERMISSION_READ, [
        Permission::PERMISSION_LIST_WORKSPACES,
        Permission::PERMISSION_LIST_FACILITIES
    ]),
    new ImpliedPermission(Permission::PERMISSION_EXPORT, [
        Permission::PERMISSION_LIST_WORKSPACES,
        Permission::PERMISSION_LIST_FACILITIES
    ]),
    new ProjectImplicitReadViaExplicitWorkspacePermission(),
    new PublicProjectRule(),
    new ProjectImpliesWorkspace(),
    new ManageWorkspaceRule(),
    new CreateFacilityRule(),
    new RespondToAccessRequestRule(),
];
