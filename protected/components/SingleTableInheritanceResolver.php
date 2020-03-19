<?php
declare(strict_types=1);

namespace prime\components;


use prime\models\ar\Element;
use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Resolver;

class SingleTableInheritanceResolver implements Resolver
{

    /**
     * @inheritDoc
     */
    public function fromSubject(object $object): ?Authorizable
    {
        if ($object instanceof Element) {
            $id = implode('|', $object->getPrimaryKey(true));
            return new \SamIT\abac\values\Authorizable($id, Element::class);
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