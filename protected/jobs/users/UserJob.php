<?php
declare(strict_types=1);

namespace prime\jobs\users;

use JCIT\jobqueue\interfaces\JobInterface;

abstract class UserJob implements JobInterface
{
    public function __construct(
        protected int $userId
    ) {
    }

    public static function fromArray(array $config): JobInterface
    {
        $class = static::class;
        return new $class($config['userId']);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function jsonSerialize(): array
    {
        return [
            'accessRequestId' => $this->userId,
        ];
    }
}
