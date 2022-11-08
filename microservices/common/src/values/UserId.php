<?php

declare(strict_types=1);

namespace herams\common\values;

use herams\common\domain\user\User;

/**
 * @codeCoverageIgnore
 */
class UserId extends IntegerId
{
    public static function fromUser(User $user): static
    {
        if (! is_integer($user->id)) {
            throw new \InvalidArgumentException('User must have an id');
        }
        return new self($user->id);
    }
}
