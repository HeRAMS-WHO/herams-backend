<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\models\Permission;
use herams\common\models\Workspace;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class CreateFacilityRule implements Rule
{
    public function getPermissions(): array
    {
        return [Permission::PERMISSION_CREATE_FACILITY];
    }

    public function getTargetNames(): array
    {
        return [Workspace::class];
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
        return $target instanceof Workspace
            && $permission === Permission::PERMISSION_CREATE_FACILITY
            && $target->project->manageWorkspacesImpliesCreatingFacilities()
            && $accessChecker->check($source, $target, Permission::PERMISSION_SURVEY_DATA)
        ;
    }
}
