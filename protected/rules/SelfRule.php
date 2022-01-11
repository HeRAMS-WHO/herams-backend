<?php
declare(strict_types=1);

namespace prime\rules;

use prime\models\ar\User;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\Rule;

class SelfRule implements Rule
{
    private array $permissions;

    public function __construct(array $permissions)
    {
        $this->permissions = $permissions;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getTargetNames(): array
    {
        return [User::class];
    }

    public function getSourceNames(): array
    {
        return [User::class];
    }

    public function getDescription(): string
    {
        return 'if you can are the user and the requested permission is in: (' . implode(", ", $this->permissions) . ")";
    }

    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        return $target instanceof User
            && $source instanceof User
            && $target->id === $source->id
            && in_array($permission, $this->permissions);
    }
}
