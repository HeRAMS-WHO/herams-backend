<?php
declare(strict_types=1);

namespace prime\rules;


use prime\models\ar\User;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class WorkspaceRule implements Rule
{

    /**
     * @inheritDoc
     */
    public function getPermissions(): array
    {
        return [Permission::PERMISSION_ADMIN];
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
        return 'you can administer the project it belongs to';
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
        die('no');
        return $target instanceof Workspace
            && $accessChecker->check($source, $target->project, Permission::PERMISSION_ADMIN);
    }
}