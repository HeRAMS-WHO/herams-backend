<?php

declare(strict_types=1);

namespace prime\tests\unit\jobs\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\accessRequests\ImplicitlyGrantedNotificationJob;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \prime\jobs\accessRequests\ImplicitlyGrantedNotificationJob
 */
class ImplicitlyGrantedNotificationJobTest extends JobTest
{
    public function createJob(): JobInterface
    {
        return new ImplicitlyGrantedNotificationJob(1, false);
    }
}
