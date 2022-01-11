<?php

declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\Element;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class DashboardRule implements Rule
{
    public function getPermissions(): array
    {
        return [
            Permission::PERMISSION_DELETE,
            Permission::PERMISSION_WRITE,
        ];
    }

    public function getTargetNames(): array
    {
        return [
            Page::class,
            Element::class
        ];
    }

    public function getSourceNames(): array
    {
        return [
            User::class
        ];
    }

    public function getDescription(): string
    {
        return 'if you have manage dashboard permissions for the project';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return in_array(get_class($source), $this->getSourceNames())
            && ($target instanceof Element || $target instanceof Page)
            && in_array($permission, $this->getPermissions())
            && $target->project instanceof Project
            && $accessChecker->check($source, $target->project, Permission::PERMISSION_MANAGE_DASHBOARD)
        ;
    }
}
