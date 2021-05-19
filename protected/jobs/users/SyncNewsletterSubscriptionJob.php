<?php
declare(strict_types=1);

namespace prime\jobs\users;

use JCIT\jobqueue\interfaces\JobInterface;

class SyncNewsletterSubscriptionJob extends UserJob
{
    public function __construct(
        protected int $userId,
        protected bool $insert = false,
    ) {
        parent::__construct($this->userId);
    }

    public static function fromArray(array $config): JobInterface
    {
        $class = static::class;
        return new $class($config['userId'], $config['insert']);
    }

    public function getInsert(): bool
    {
        return $this->insert;
    }

    public function jsonSerialize(): array
    {
        return [
            'accessRequestId' => $this->userId,
            'insert' => $this->insert,
        ];
    }
}
