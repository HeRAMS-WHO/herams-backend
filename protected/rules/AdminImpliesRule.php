<?php
declare(strict_types=1);

namespace prime\rules;


use prime\models\permissions\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\SimpleRule;

class AdminImpliesRule implements SimpleRule
{

    /**
     * @return string A human readable description of what this rule does.
     * Finish the sentence: "You can [permission] the [object] if.."
     */
    public function getDescription(): string
    {
        return "you admin the object";
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
        return $permission !== Permission::PERMISSION_ADMIN && $accessChecker->check($source, $target, Permission::PERMISSION_ADMIN);
    }
}