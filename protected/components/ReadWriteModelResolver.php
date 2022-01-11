<?php
declare(strict_types=1);

namespace prime\components;

use prime\models\ar\Element;
use prime\models\ar\read\Project;
use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Resolver;

/**
 * This resolver will resolve a child class to the parent's class name if they have the same short name.
 * This allows for using read / write models with permission checks on the base AR models.
 */
class ReadWriteModelResolver implements Resolver
{
    /**
     * @inheritDoc
     */
    public function fromSubject(object $object): ?Authorizable
    {
        $rc = new \ReflectionClass($object);

        if (($rc->getParentClass() ?: null )?->getShortName() == $rc->getShortName()) {
            $id = implode('|', $object->getPrimaryKey(true));
            return new \SamIT\abac\values\Authorizable($id, $rc->getParentClass()->getName());
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function toSubject(Authorizable $authorizable): ?object
    {
        return null;
    }
}
