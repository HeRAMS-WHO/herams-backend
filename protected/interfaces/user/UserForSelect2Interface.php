<?php

declare(strict_types=1);

namespace prime\interfaces\user;

use herams\common\values\UserId;

interface UserForSelect2Interface
{
    public function getText(): string;

    public function getUserId(): UserId|null;
}
