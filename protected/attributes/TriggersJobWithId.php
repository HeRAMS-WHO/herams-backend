<?php

declare(strict_types=1);

namespace prime\attributes;

use Attribute;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\interfaces\Dehydrator;
use prime\objects\enums\AuditEvent;

/**
 * @template T
 */
#[Attribute(Attribute::TARGET_CLASS)]
class TriggersJobWithId
{
    /**
     * @param class-string<T> $jobClass
     */
    public function __construct(private string $jobClass)
    {
    }

    /**
     * @return T
     */
    public function create(int $id): JobInterface
    {
        return new ($this->jobClass)($id);
    }
}
