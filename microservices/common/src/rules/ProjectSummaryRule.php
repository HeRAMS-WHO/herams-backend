<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\models\PermissionOld;
use herams\common\models\Project;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class ProjectSummaryRule implements Rule
{
    public function getPermissions(): array
    {
        return [PermissionOld::PERMISSION_SUMMARY];
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
        return 'it is not hidden (so public or private), or you have the READ permission';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return $target instanceof Project
            && in_array($permission, $this->getPermissions())
            && (! $target->isHidden() || $accessChecker->check($source, $target, PermissionOld::PERMISSION_READ));
    }
}
