<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\domain\element\Element;
use herams\common\domain\user\User;
use herams\common\models\Page;
use herams\common\models\PermissionOld;
use herams\common\models\Project;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class DashboardRule implements Rule
{
    public function getPermissions(): array
    {
        return [
            PermissionOld::PERMISSION_DELETE,
            PermissionOld::PERMISSION_WRITE,
        ];
    }

    public function getTargetNames(): array
    {
        return [
            Page::class,
            Element::class,
        ];
    }

    public function getSourceNames(): array
    {
        return [
            User::class,
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
            && $accessChecker->check($source, $target->project, PermissionOld::PERMISSION_MANAGE_DASHBOARD)
        ;
    }
}
