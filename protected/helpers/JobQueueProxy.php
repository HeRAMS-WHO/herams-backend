<?php

declare(strict_types=1);

namespace prime\helpers;

use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;

class JobQueueProxy
{
    public function __construct(private JobQueueInterface $jobQueue)
    {
    }


    public function get(): JobQueueInterface
    {
        return $this->jobQueue;
    }
}
