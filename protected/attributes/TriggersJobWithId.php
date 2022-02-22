<?php

declare(strict_types=1);

namespace prime\attributes;

use Attribute;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\interfaces\Dehydrator;
use prime\objects\enums\AuditEvent;

#[Attribute(Attribute::TARGET_CLASS)]
class TriggersJobWithId
{
    /**
     * @param class-string $jobClass
     */
    public function __construct(private string $jobClass)
    {
    }

    /**
     * @todo add return type hint for phpstan
     */
    public function create(int $id): JobInterface
    {
        return new ($this->jobClass)($id);
    }
}
