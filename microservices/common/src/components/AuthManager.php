<?php

declare(strict_types=1);

namespace herams\common\components;

use SamIT\abac\interfaces\AccessChecker as AccessCheckerInterface;
use SamIT\Yii2\abac\AccessChecker;

class AuthManager extends AccessChecker implements AccessCheckerInterface
{
    public function __construct(
        private readonly \SamIT\abac\AuthManager $manager,
        string $userClass
    ) {
        parent::__construct($manager, $userClass);
    }

    public function checkAccess($userId, $permissionName, $params = []): bool
    {
        if (is_object($params)) {
            $params = [
                self::TARGET_PARAM => $params,
            ];
        }
        return parent::checkAccess($userId, $permissionName, $params);
    }

    public function check(object $source, object $target, string $permission): bool
    {
        return $this->manager->check($source, $target, $permission);
    }
}
