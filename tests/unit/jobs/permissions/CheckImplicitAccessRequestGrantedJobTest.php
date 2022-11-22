<?php

declare(strict_types=1);

namespace prime\tests\unit\jobs\permissions;

use herams\common\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \herams\common\jobs\permissions\CheckImplicitAccessRequestGrantedJob
 * @covers \herams\common\jobs\permissions\PermissionJob
 */
class CheckImplicitAccessRequestGrantedJobTest extends JobTest
{
    public function createJob(): JobInterface
    {
        return new CheckImplicitAccessRequestGrantedJob(1);
    }
}
