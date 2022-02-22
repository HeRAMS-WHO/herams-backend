<?php

declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class ManageWorkspaceRule implements Rule
{
    /**
     * @inheritDoc
     */
    public function getPermissions(): array
    {
        return [];
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
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'if you can manage workspaces for the project';
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
        return $target instanceof Workspace
            // This permission is handled in a separate rule
            && $permission !== Permission::PERMISSION_CREATE_FACILITY
            && $accessChecker->check($source, $target->project, Permission::PERMISSION_MANAGE_WORKSPACES)
        ;
    }
}
