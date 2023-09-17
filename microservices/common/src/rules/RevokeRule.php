<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\domain\permission\ProposedGrant;
use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class RevokeRule implements Rule
{
    public function getPermissions(): array
    {
        return [PermissionOld::PERMISSION_DELETE];
    }

    public function getTargetNames(): array
    {
        return [ProposedGrant::class];
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
            && $source instanceof User
            && ($proposedSource = $target->getSource()) instanceof User
            && $source->id !== $proposedSource->id
            && ($permission === PermissionOld::PERMISSION_DELETE)
            // To share or revoke you must have share permissions
            && $accessChecker->check($source, $target->getTarget(), PermissionOld::PERMISSION_SHARE)
            // To share you must have the permission that you are giving
            && $accessChecker->check($source, $target->getTarget(), $target->getPermission());
    }
}
