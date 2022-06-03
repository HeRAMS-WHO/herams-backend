<?php

declare(strict_types=1);

namespace prime\jobs\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;

class ImplicitlyGrantedNotificationJob extends AccessRequestJob
{
    public function __construct(
        int $accessRequestId,
        private bool $partial
    ) {
        parent::__construct($accessRequestId);
    }

    public static function fromArray(array $config): JobInterface
    {
        return new self($config['accessRequestId'], $config['partial']);
    }

    public function getPartial(): bool
    {
        return $this->partial;
    }

    public function jsonSerialize(): array
    {
        /**
         * @todo When we upgrade to php 8.1 use spread operator
         */
        return array_merge(
            parent::jsonSerialize(),
            [
                'partial' => $this->partial,
            ]
        );
    }
}
