<?php

declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\Facility;
use prime\models\ar\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class FacilityCascadeWorkspaceRule implements Rule
{

    public function getPermissions(): array
    {
        return [
            Permission::PERMISSION_WRITE,
        ];
    }

    public function getTargetNames(): array
    {
        return [Facility::class];
    }

    public function getSourceNames(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return 'if you can manage workspaces for the project and the manage implies create HF setting is enabled';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        if (!($target instanceof Facility && in_array($permission, $this->getPermissions()))) {
            return false;
        }
        return $accessChecker->check($source, $target->workspace, Permission::PERMISSION_CREATE_FACILITY)
            || (
                $accessChecker->check($source, $target->workspace, Permission::PERMISSION_SURVEY_DATA)
                && $target->workspace->project->manageWorkspacesImpliesCreatingFacilities()
            );
    }
}
