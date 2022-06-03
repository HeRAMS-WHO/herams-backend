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

class ProjectImplicitReadViaExplicitWorkspacePermission implements Rule
{
    public function getPermissions(): array
    {
        return [Permission::PERMISSION_READ];
    }

    public function getTargetNames(): array
    {
        return [Project::class];
    }

    public function getSourceNames(): array
    {
        return [User::class];
    }

    public function getDescription(): string
    {
        return 'if you can read any of the workspaces';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return $source instanceof User
            && $target instanceof Project
            && $permission === Permission::PERMISSION_READ
            && Permission::find()
                ->andWhere([
                    'source_id' => $source->getId(),
                    'source' => User::class,
                    'target_id' => $target->getWorkspaces()->select('id'),
                    'target' => Workspace::class,
                ])->exists();
    }
}
