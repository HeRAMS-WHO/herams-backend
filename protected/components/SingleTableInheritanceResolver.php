<?php
declare(strict_types=1);

namespace prime\components;


use prime\helpers\ProposedGrant;
use prime\models\ar\Element;
use prime\models\permissions\GlobalPermission;
use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Grant;
use SamIT\Yii2\abac\AccessChecker;
use yii\base\NotSupportedException;

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