<?php

declare(strict_types=1);

namespace herams\common\attributes;

use Attribute;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\interfaces\QueueJobFromModelInterface;
use yii\db\ActiveRecordInterface;

/**
 * This attribute triggers a job with the given class and the primary model ID.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class TriggersJob implements QueueJobFromModelInterface
{
    /**
     * @param class-string<JobInterface> $jobClass
     */
    public function __construct(
        private string $jobClass,
        private string $attribute = 'id'
    ) {
    }

    public function create(ActiveRecordInterface $model): JobInterface
    {
        return new ($this->jobClass)($model->getAttribute($this->attribute));
    }
}
