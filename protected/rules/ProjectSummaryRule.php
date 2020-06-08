<?php
declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\Project;
use prime\models\permissions\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class ProjectSummaryRule implements Rule
{

    public function getPermissions(): array
    {
        return [Permission::PERMISSION_SUMMARY];
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
        if (in_array($target, $this->getTargetNames())
            && in_array($permission, $this->getPermissions())) {
            var_dump($target->isHidden());
        }
        return $target instanceof Project
            && in_array($permission, $this->getPermissions())
            && (!$target->isHidden() || $accessChecker->check($source, $target, Permission::PERMISSION_READ));
    }
}
