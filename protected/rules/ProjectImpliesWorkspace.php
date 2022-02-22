<?php

declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class ProjectImpliesWorkspace implements Rule
{
    /**
     * @inheritDoc
     */
    public function getPermissions(): array
    {
        return [
            Permission::PERMISSION_SHARE,
            Permission::PERMISSION_EXPORT,
            Permission::PERMISSION_SURVEY_DATA,
            Permission::PERMISSION_ADMIN,
            Permission::PERMISSION_DELETE,
            Permission::PERMISSION_CREATE_FACILITY
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTargetNames(): array
    {
        return [Workspace::class];
    }

    /**
     * @inheritDoc
     */
    public function getSourceNames(): array
    {
        return [User::class];
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'if you can share the project it belongs to';
    }

    /**
     * @inheritDoc
     */
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
