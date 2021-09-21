<?php
declare(strict_types=1);

namespace prime\interfaces\user;

use prime\values\UserId;

interface UserForSelect2Interface
{
    public function getText(): string;
    public function getUserId(): UserId|null;
}
