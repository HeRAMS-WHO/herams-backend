<?php
declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\Facility;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class CreateFacilityRule implements Rule
{

    public function getPermissions(): array
    {
        return [Permission::PERMISSION_CREATE];
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
        if (!($target instanceof Facility && $permission === Permission::PERMISSION_CREATE)) {
            return false;
        }
        $workspace = Workspace::findOne(['id' => $target->workspace_id]);
        return $accessChecker->check($source, $workspace, Permission::PERMISSION_CREATE_FACILITY)
            || (
                $accessChecker->check($source, $target, Permission::PERMISSION_SURVEY_DATA)
                && $workspace->project->manageWorkspacesImpliesCreatingFacilities()
            );
    }
}
