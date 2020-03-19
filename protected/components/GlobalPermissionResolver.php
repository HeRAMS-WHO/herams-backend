<?php
declare(strict_types=1);

namespace prime\components;


use prime\models\permissions\GlobalPermission;
use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\abac\AccessChecker;

class GlobalPermissionResolver implements Resolver
{

    /**
     * @inheritDoc
     */
    public function fromSubject(object $object): ?Authorizable
    {
        if ($object instanceof GlobalPermission) {
            return $object;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function toSubject(Authorizable $authorizable): ?object
    {
        if ($authorizable->getAuthName() === AccessChecker::BUILTIN && $authorizable->getId() === AccessChecker::GLOBAL) {
            return new GlobalPermission();
        }
        return null;
    }
}