<?php

declare(strict_types=1);

namespace herams\common\rules;

use herams\common\domain\user\User;
use herams\common\models\Permission;
use prime\models\ar\AccessRequest;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class RespondToAccessRequestRule implements Rule
{
    public function getPermissions(): array
    {
        return [
            Permission::PERMISSION_RESPOND,
        ];
    }

    public function getTargetNames(): array
    {
        return [
            AccessRequest::class,
        ];
    }

    public function getSourceNames(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return 'if you have share permission on the target';
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        /** @var AccessRequest $target */
        return $source instanceof User
            && $target instanceof AccessRequest
            && $target->created_by !== $source->id
            && isset($target->target)
            && $accessChecker->check($source, $target->target, Permission::PERMISSION_SHARE);
    }
}
