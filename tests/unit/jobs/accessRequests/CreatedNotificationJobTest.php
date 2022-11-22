<?php

declare(strict_types=1);

namespace prime\tests\unit\jobs\accessRequests;

use herams\common\jobs\accessRequests\CreatedNotificationJob;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \herams\common\jobs\accessRequests\CreatedNotificationJob
 * @covers \herams\common\jobs\accessRequests\AccessRequestJob
 */
class CreatedNotificationJobTest extends JobTest
{
    public function createJob(): JobInterface
    {
        return new CreatedNotificationJob(1);
    }
}
