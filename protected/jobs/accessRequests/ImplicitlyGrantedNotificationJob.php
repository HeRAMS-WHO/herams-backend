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
        return [
            'accessRequestId' => $this->accessRequestId,
            'partial' => $this->partial,
        ];
    }
}
