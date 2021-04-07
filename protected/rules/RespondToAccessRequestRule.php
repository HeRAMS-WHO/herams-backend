<?php
declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class RespondToAccessRequestRule implements Rule
{

    /**
     * @inheritDoc
     */
    public function getPermissions(): array
    {
        return [
            Permission::PERMISSION_RESPOND,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTargetNames(): array
    {
        return [
            AccessRequest::class,
        ];
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
        return 'if you can grant the access request';
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
        /** @var AccessRequest $target */
        return $target instanceof AccessRequest
            && $accessChecker->check($source, $target->target, Permission::PERMISSION_SHARE);
    }
}
