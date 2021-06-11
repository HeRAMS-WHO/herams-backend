<?php
declare(strict_types=1);

namespace prime\interfaces;

interface CanCurrentUser
{

    public function canCurrentUser(string $permission): bool;
}
