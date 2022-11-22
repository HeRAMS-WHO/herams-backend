<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\domain\element\Element;
use SamIT\abac\exceptions\UnresolvableException;
use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Resolver;

final class SingleTableInheritanceResolver implements Resolver
{
    public function fromSubject(object $object): Authorizable
    {
        if ($object instanceof Element) {
            $id = implode('|', $object->getPrimaryKey(true));
            return new \SamIT\abac\values\Authorizable($id, Element::class);
        }
        throw new UnresolvableException();
    }

    public function toSubject(Authorizable $authorizable): never
    {
        throw new UnresolvableException();
    }
}
