<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use herams\common\models\Project;
use herams\common\models\Workspace;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class ProjectImpliesWorkspace implements Rule
{
    public function getPermissions(): array
    {
        return [
            PermissionOld::PERMISSION_SHARE,
            PermissionOld::PERMISSION_EXPORT,
            PermissionOld::PERMISSION_SURVEY_DATA,
            PermissionOld::PERMISSION_ADMIN,
            PermissionOld::PERMISSION_DELETE,
            PermissionOld::PERMISSION_CREATE_FACILITY,
        ];
    }

    public function getTargetNames(): array
    {
        return [Workspace::class];
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
            && $target instanceof Workspace
            && in_array($permission, $this->getPermissions())
            && $target->project instanceof Project
            && $accessChecker->check($source, $target->project, $permission);
    }
}
