<?php

declare(strict_types=1);

namespace prime\tests\unit\jobs\users;

use herams\common\jobs\users\SyncNewsletterSubscriptionJob;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \herams\common\jobs\users\SyncNewsletterSubscriptionJob
 * @covers \herams\common\jobs\users\UserJob
 */
class SyncNewsletterSubscriptionJobTest extends JobTest
{
    public function createJob(): JobInterface
    {
        return new SyncNewsletterSubscriptionJob(1, false);
    }
}
