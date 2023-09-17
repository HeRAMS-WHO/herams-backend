<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\domain\permission\ProposedGrant;
use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;
use SamIT\abac\values\Grant;

class GrantRule implements Rule
{
    public function getPermissions(): array
    {
        return [PermissionOld::PERMISSION_CREATE];
    }

    public function getTargetNames(): array
    {
        return [Grant::class];
    }

    public function getSourceNames(): array
    {
        return [User::class];
    }

    public function getDescription(): string
    {
        return 'you have the share permission on its target and you are not trying to grant share permissions';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return $target instanceof ProposedGrant
            && ($permission === PermissionOld::PERMISSION_CREATE)
            // This rule will never grant someone permission to create a grant with a share permission
            && ! in_array($target->getPermission(), [PermissionOld::PERMISSION_SHARE, PermissionOld::PERMISSION_SUPER_SHARE])
            // To share you must have share permissions
            && $accessChecker->check($source, $target->getTarget(), PermissionOld::PERMISSION_SHARE)
            // To share you must have the permission that you are giving
            && $accessChecker->check($source, $target->getTarget(), $target->getPermission());
    }
}
