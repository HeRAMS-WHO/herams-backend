<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\models\GlobalPermission;
use herams\common\models\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\SimpleRule;

class AdminRule implements SimpleRule
{
    public function getDescription(): string
    {
        return 'you have global admin permissions';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return ! $target instanceof GlobalPermission
            && $accessChecker->check($source, new GlobalPermission(), Permission::PERMISSION_ADMIN);
    }
}
