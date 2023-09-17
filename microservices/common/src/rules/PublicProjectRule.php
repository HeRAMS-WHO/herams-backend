<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\models\PermissionOld;
use herams\common\models\Project;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class PublicProjectRule implements Rule
{
    public function getPermissions(): array
    {
        return [PermissionOld::PERMISSION_READ];
    }

    public function getTargetNames(): array
    {
        return [Project::class];
    }

    public function getSourceNames(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return 'if it is public';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return $target instanceof Project
            && $target->visibility === Project::VISIBILITY_PUBLIC
            && in_array($permission, $this->getPermissions());
    }
}
