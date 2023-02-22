<?php

declare(strict_types=1);

use herams\common\models\Permission;
use herams\common\rules\AdminRule;
use herams\common\rules\AdminShareRule;
use herams\common\rules\CreateFacilityCascadeWorkspaceRule;
use herams\common\rules\CreateFacilityRule;
use herams\common\rules\DashboardRule;
use herams\common\rules\FacilityCascadeWorkspaceRule;
use herams\common\rules\FacilityListResponsesRule;
use herams\common\rules\GrantRule;
use herams\common\rules\ManageWorkspaceRule;
use herams\common\rules\ProjectImplicitReadViaExplicitWorkspacePermission;
use herams\common\rules\ProjectImpliesWorkspace;
use herams\common\rules\ProjectSummaryRule;
use herams\common\rules\PublicProjectRule;
use herams\common\rules\RespondToAccessRequestRule;
use herams\common\rules\RevokeRule;
use herams\common\rules\SelfRule;
use herams\common\rules\SuperShareRule;
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
        Permission::PERMISSION_CREATE_FACILITY,
    ]),
    new ImpliedPermission(Permission::PERMISSION_WRITE, [
        Permission::PERMISSION_READ,
    ]),
    new ImpliedPermission(Permission::PERMISSION_SURVEY_DATA, [
        Permission::PERMISSION_READ,
    ]),
    new ImpliedPermission(Permission::PERMISSION_READ, [
        Permission::PERMISSION_LIST_WORKSPACES,
        Permission::PERMISSION_LIST_FACILITIES,
    ]),
    new ImpliedPermission(Permission::PERMISSION_EXPORT, [
        Permission::PERMISSION_LIST_WORKSPACES,
        Permission::PERMISSION_READ,
        Permission::PERMISSION_LIST_FACILITIES,
    ]),
    new ProjectImplicitReadViaExplicitWorkspacePermission(),
    new PublicProjectRule(),
    new ProjectImpliesWorkspace(),
    new ManageWorkspaceRule(),
    new RespondToAccessRequestRule(),
    new FacilityListResponsesRule(),
];
