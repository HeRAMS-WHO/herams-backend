<?php
declare(strict_types=1);

namespace prime\components;


use prime\helpers\ProposedGrant;
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
        if ($object instanceof ProposedGrant) {
            return null;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function toSubject(Authorizable $authorizable): ?object
    {
        if ($authorizable instanceof ProposedGrant) {
            return $authorizable;
        }
        return null;
    }
}