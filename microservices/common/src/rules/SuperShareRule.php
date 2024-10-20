<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\domain\permission\ProposedGrant;
use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class SuperShareRule implements Rule
{
    public function getPermissions(): array
    {
        return [PermissionOld::PERMISSION_CREATE];
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
        return in_array(get_class($target), $this->getTargetNames())
            && in_array($permission, $this->getPermissions())
            // This rule will only grant someone permission to create a grant with the share permission
            && $target->getPermission() === PermissionOld::PERMISSION_SHARE
            // To share the share permission you must have super share permissions
            && $accessChecker->check($source, $target->getTarget(), PermissionOld::PERMISSION_SUPER_SHARE);
    }
}
