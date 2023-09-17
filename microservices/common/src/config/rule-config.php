<?php

declare(strict_types=1);

use herams\common\models\PermissionOld;
use herams\common\rules\AdminRule;
use herams\common\rules\AdminShareRule;
use herams\common\rules\DashboardRule;
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
    new SelfRule([PermissionOld::PERMISSION_MANAGE_FAVORITES]),
    new GrantRule(),
    new ProjectSummaryRule(),
    new RevokeRule(),
    new DashboardRule(),
    new SuperShareRule(),
    new AdminShareRule(),
    new ImpliedPermission(PermissionOld::PERMISSION_ADMIN, [
        PermissionOld::PERMISSION_SHARE,
        PermissionOld::PERMISSION_WRITE,
        PermissionOld::PERMISSION_DELETE,
        PermissionOld::PERMISSION_EXPORT,
        PermissionOld::PERMISSION_SURVEY_DATA,
        PermissionOld::PERMISSION_MANAGE_WORKSPACES,
        PermissionOld::PERMISSION_MANAGE_DASHBOARD,
        PermissionOld::PERMISSION_DELETE_ALL_WORKSPACES,
        PermissionOld::PERMISSION_CREATE_FACILITY,
    ]),
    new ImpliedPermission(PermissionOld::PERMISSION_WRITE, [
        PermissionOld::PERMISSION_READ,
    ]),
    new ImpliedPermission(PermissionOld::PERMISSION_SURVEY_DATA, [
        PermissionOld::PERMISSION_READ,
    ]),
    new ImpliedPermission(PermissionOld::PERMISSION_READ, [
        PermissionOld::PERMISSION_LIST_WORKSPACES,
        PermissionOld::PERMISSION_LIST_FACILITIES,
    ]),
    new ImpliedPermission(PermissionOld::PERMISSION_EXPORT, [
        PermissionOld::PERMISSION_LIST_WORKSPACES,
        PermissionOld::PERMISSION_READ,
        PermissionOld::PERMISSION_LIST_FACILITIES,
    ]),
    new ProjectImplicitReadViaExplicitWorkspacePermission(),
    new PublicProjectRule(),
    new ProjectImpliesWorkspace(),
    new ManageWorkspaceRule(),
    new RespondToAccessRequestRule(),
    new FacilityListResponsesRule(),
];
