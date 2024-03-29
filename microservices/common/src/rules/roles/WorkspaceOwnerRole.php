<?php

declare(strict_types=1);

namespace herams\common\rules\roles;

use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use herams\common\models\Workspace;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class WorkspaceOwnerRole implements Rule
{
    public function getPermissions(): array
    {
        return [
            PermissionOld::ROLE_WORKSPACE_CONTRIBUTOR,
            PermissionOld::PERMISSION_SHARE,
        ];
    }

    public function getTargetNames(): array
    {
        return [Workspace::class];
    }

    public function getSourceNames(): array
    {
        return [User::class];
    }

    public function getDescription(): string
    {
        return 'have the correct role';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return in_array(get_class($target), $this->getTargetNames())
            && in_array(get_class($source), $this->getSourceNames())
            && in_array($permission, $this->getPermissions())
            && $accessChecker->check($source, $target, PermissionOld::ROLE_WORKSPACE_OWNER);
    }
}
