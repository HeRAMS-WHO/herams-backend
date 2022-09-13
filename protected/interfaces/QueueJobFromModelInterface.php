<?php

declare(strict_types=1);

namespace prime\interfaces;

use JCIT\jobqueue\interfaces\JobInterface;
use yii\db\ActiveRecordInterface;

interface QueueJobFromModelInterface
{
    public function create(ActiveRecordInterface $model): JobInterface;
}
