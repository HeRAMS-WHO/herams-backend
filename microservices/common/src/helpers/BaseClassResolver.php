<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\domain\user\User;
use herams\common\models\Project;
use SamIT\abac\exceptions\UnresolvableException;
use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Resolver;

/**
 * Resolves names by stripping the namespace from the class
 */
final class BaseClassResolver implements Resolver
{
    public function fromSubject(object $object): Authorizable
    {
        if ($object instanceof Authorizable) {
            return $object;
        } elseif ($object instanceof \yii\db\ActiveRecord && ! $object->getIsNewRecord()) {
            $primaryKey = $object->getPrimaryKey(false);
            if (! is_scalar($primaryKey)) {
                throw UnresolvableException::forSubject($object);
            }
            $rc = new \ReflectionClass($object);
            return new \SamIT\abac\values\Authorizable((string) $primaryKey, $rc->getShortName());
        }
        throw UnresolvableException::forSubject($object);
    }

    public function toSubject(Authorizable $authorizable): object
    {
        // Check if the class exists in our model namespace.
        return match ($authorizable->getAuthName()) {
            'User' => User::findOne([
                'id' => $authorizable->getId(),
            ]),
            'Project' => Project::findOne([
                'id' => $authorizable->getId(),
            ]),
            default => throw new UnresolvableException()
        };
    }
}
