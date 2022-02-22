<?php

declare(strict_types=1);

namespace prime\tests\unit\jobs\users;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\users\SyncNewsletterSubscriptionJob;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \prime\jobs\users\SyncNewsletterSubscriptionJob
 * @covers \prime\jobs\users\UserJob
 */
class SyncNewsletterSubscriptionJobTest extends JobTest
{
    public function createJob(): JobInterface
    {
        return new SyncNewsletterSubscriptionJob(1, false);
    }
}
