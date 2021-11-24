<?php

declare(strict_types=1);

namespace prime\rules;

use prime\helpers\ProposedGrant;
use prime\models\ar\Permission;
use prime\models\ar\User;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;
use SamIT\abac\values\Grant;

class GrantRule implements Rule
{
    /**
     * @inheritDoc
     */
    public function getPermissions(): array
    {
        return [Permission::PERMISSION_CREATE];
    }

    /**
     * @inheritDoc
     */
    public function getTargetNames(): array
    {
        return [Grant::class];
    }

    /**
     * @inheritDoc
     */
    public function getSourceNames(): array
    {
        return [User::class];
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'you have the share permission on its target and you are not trying to grant share permissions';
    }

    /**
     * @inheritDoc
     */
    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return $target instanceof ProposedGrant
            && ($permission === Permission::PERMISSION_CREATE)
            // This rule will never grant someone permission to create a grant with a share permission
            && !in_array($target->getPermission(), [Permission::PERMISSION_SHARE, Permission::PERMISSION_SUPER_SHARE])
            // To share you must have share permissions
            && $accessChecker->check($source, $target->getTarget(), Permission::PERMISSION_SHARE)
            // To share you must have the permission that you are giving
            && $accessChecker->check($source, $target->getTarget(), $target->getPermission());
    }
}
