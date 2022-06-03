<?php

declare(strict_types=1);

namespace prime\rules;

use prime\helpers\ProposedGrant;
use prime\models\ar\Permission;
use prime\models\ar\User;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class RevokeRule implements Rule
{
    public function getPermissions(): array
    {
        return [Permission::PERMISSION_DELETE];
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
            && ($permission === Permission::PERMISSION_DELETE)
            // To share or revoke you must have share permissions
            && $accessChecker->check($source, $target->getTarget(), Permission::PERMISSION_SHARE)
            // To share you must have the permission that you are giving
            && $accessChecker->check($source, $target->getTarget(), $target->getPermission());
    }
}
