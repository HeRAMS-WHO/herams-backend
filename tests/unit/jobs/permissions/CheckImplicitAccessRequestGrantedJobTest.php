<?php
declare(strict_types=1);

namespace prime\tests\unit\jobs\permissions;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob
 * @covers \prime\jobs\permissions\PermissionJob
 */
class CheckImplicitAccessRequestGrantedJobTest extends JobTest
{
    public function createJob(): JobInterface
    {
        return new CheckImplicitAccessRequestGrantedJob(1);
    }
}
