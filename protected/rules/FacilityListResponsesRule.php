<?php

declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\Facility;
use prime\models\ar\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class FacilityListResponsesRule implements Rule
{

    public function getPermissions(): array
    {
        return [
            Permission::PERMISSION_LIST_ADMIN_RESPONSES,
            Permission::PERMISSION_LIST_DATA_RESPONSES,
        ];
    }

    public function getTargetNames(): array
    {
        return [
            Facility::class
        ];
    }

    public function getSourceNames(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return 'if you can manage the survey data of the workspace.';
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

        return
            $accessChecker->check($source, $target->workspace, Permission::PERMISSION_SURVEY_DATA)
            && $target->workspace->project->manageWorkspacesImpliesCreatingFacilities()
        ;
    }
}
