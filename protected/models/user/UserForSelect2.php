<?php

declare(strict_types=1);

namespace prime\models\user;

use prime\interfaces\user\UserForSelect2Interface;
use prime\models\ar\User;
use prime\values\UserId;

class UserForSelect2 implements UserForSelect2Interface
{
    private string $text;
    private UserId $userId;

    public function __construct(
        User $user
    ) {
        $this->text = "{$user->name} ({$user->email})";
        $this->userId = new UserId($user->id);
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
