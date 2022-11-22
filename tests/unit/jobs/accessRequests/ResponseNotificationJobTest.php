<?php

declare(strict_types=1);

namespace prime\tests\unit\jobs\accessRequests;

use herams\common\jobs\accessRequests\ResponseNotificationJob;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \herams\common\jobs\accessRequests\ResponseNotificationJob
 */
class ResponseNotificationJobTest extends JobTest
{
    public function createJob(): JobInterface
    {
        return new ResponseNotificationJob(1);
    }
}
