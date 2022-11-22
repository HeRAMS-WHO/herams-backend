<?php

declare(strict_types=1);

namespace prime\tests\unit\jobs\accessRequests;

use herams\common\jobs\accessRequests\ImplicitlyGrantedNotificationJob;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \herams\common\jobs\accessRequests\ImplicitlyGrantedNotificationJob
 */
class ImplicitlyGrantedNotificationJobTest extends JobTest
{
    public function createJob(): JobInterface
    {
        return new ImplicitlyGrantedNotificationJob(1, false);
    }
}
