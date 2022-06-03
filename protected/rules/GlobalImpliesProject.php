<?php

declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\permissions\GlobalPermission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class GlobalImpliesProject implements Rule
{
    public function getPermissions(): array
    {
        return [
            Permission::PERMISSION_SHARE,
            Permission::PERMISSION_EXPORT,
            Permission::PERMISSION_SURVEY_DATA,
            Permission::PERMISSION_ADMIN,
        ];
    }

    public function getTargetNames(): array
    {
        return [Project::class];
    }

    public function getSourceNames(): array
    {
        return [User::class];
    }

    public function getDescription(): string
    {
        return 'if you can share the project it belongs to';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return in_array(get_class($source), $this->getSourceNames())
            && $target instanceof Project
            && in_array($permission, $this->getPermissions())
            && $accessChecker->check($source, new GlobalPermission(), $permission);
    }
}
