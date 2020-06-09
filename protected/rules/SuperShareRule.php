<?php
declare(strict_types=1);

namespace prime\rules;


use prime\helpers\ProposedGrant;
use prime\models\ar\Permission;
use prime\models\ar\User;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class SuperShareRule implements Rule
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
        return [ProposedGrant::class];
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
        return in_array(get_class($target), $this->getTargetNames())
            && in_array($permission, $this->getPermissions())
            // This rule will only grant someone permission to create a grant with the share permission
            && $target->getPermission() === Permission::PERMISSION_SHARE
            // To share the share permission you must have super share permissions
            && $accessChecker->check($source, $target->getTarget(), Permission::PERMISSION_SUPER_SHARE);
    }
}