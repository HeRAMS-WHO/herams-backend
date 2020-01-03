<?php
declare(strict_types=1);

namespace prime\rules;


use prime\models\ar\User;
use prime\models\permissions\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;
use SamIT\abac\values\Grant;

class RevokeRule implements Rule
{

    /**
     * @inheritDoc
     */
    public function getPermissions(): array
    {
        return [Permission::PERMISSION_DELETE];
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
        return 'you have the share permission on its target and you are not trying to revoke share permissions';
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
        return $target instanceof Grant
            && $permission === Permission::PERMISSION_DELETE
            // This rule will never grant someone permission to delete a grant with the share permission
            && $target->getPermission() !== Permission::PERMISSION_SHARE
            // To revoke a share you must have share permissions
            && $accessChecker->check($source, $target->getTarget(), Permission::PERMISSION_SHARE)
            // To revoke a share you must have the permission that you are removing
            && $accessChecker->check($source, $target->getTarget(), $target->getPermission());
    }
}