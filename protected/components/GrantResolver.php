<?php
declare(strict_types=1);

namespace prime\components;


use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Grant;
use yii\base\NotSupportedException;

class GrantResolver implements Resolver
{

    /**
     * @inheritDoc
     */
    public function fromSubject(object $object): ?Authorizable
    {
        if ($object instanceof Grant) {
            return new \SamIT\abac\values\Authorizable(implode('|', [
                $object->getPermission(),
                $object->getSource()->getAuthName(),
                $object->getSource()->getId(),
                $object->getTarget()->getAuthName(),
                $object->getTarget()->getId()
            ]), Grant::class);
        }
    }

    /**
     * @inheritDoc
     */
    public function toSubject(Authorizable $authorizable): ?object
    {
        throw new NotSupportedException();
    }
}