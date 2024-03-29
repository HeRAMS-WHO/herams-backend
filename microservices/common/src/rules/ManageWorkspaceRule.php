<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\models\PermissionOld;
use herams\common\models\Workspace;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class ManageWorkspaceRule implements Rule
{
    public function getPermissions(): array
    {
        return [];
    }

    public function getTargetNames(): array
    {
        return [Workspace::class];
    }

    public function getSourceNames(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return 'if you can manage workspaces for the project';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return $target instanceof Workspace
            // This permission is handled in a separate rule
            && $permission !== PermissionOld::PERMISSION_CREATE_FACILITY
            && $accessChecker->check($source, $target->project, PermissionOld::PERMISSION_MANAGE_WORKSPACES)
        ;
    }
}
