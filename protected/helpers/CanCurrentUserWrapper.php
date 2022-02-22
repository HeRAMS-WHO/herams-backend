<?php

declare(strict_types=1);

namespace prime\helpers;

use prime\interfaces\AccessCheckInterface;
use prime\interfaces\CanCurrentUser;

class CanCurrentUserWrapper implements CanCurrentUser
{
    use \prime\traits\CanCurrentUser;

    public function __construct(
        private AccessCheckInterface $accessCheck,
        private object $subject,
    ) {
    }

    private function getModel(): object
    {
        return $this->subject;
    }
}
