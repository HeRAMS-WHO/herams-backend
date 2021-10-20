<?php

declare(strict_types=1);

namespace prime\jobs\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;

abstract class AccessRequestJob implements JobInterface
{
    public function __construct(
        protected int $accessRequestId
    ) {
    }

    public static function fromArray(array $config): JobInterface
    {
        $class = static::class;
        return new $class($config['accessRequestId']);
    }

    public function getAccessRequestId(): int
    {
        return $this->accessRequestId;
    }

    public function jsonSerialize(): array
    {
        return [
            'accessRequestId' => $this->accessRequestId,
        ];
    }
}
