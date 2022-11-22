<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use herams\common\domain\user\User;
use herams\common\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use herams\common\models\Permission;
use herams\common\models\Project;
use JCIT\jobqueue\interfaces\JobQueueInterface;

/**
 * @covers \herams\common\models\Permission
 */
class PermissionTest extends ActiveRecordTest
{
    public function invalidSamples(): array
    {
        return [];
    }

    public function validSamples(): array
    {
        return [];
    }

    public function testGetSourceAuthorizable(): void
    {
        $permission = new Permission();
        $permission->source_id = "1";
        $permission->source = 'source';
        $this->assertSame($permission->source_id, $permission->sourceAuthorizable()->getId());
        $this->assertSame($permission->source, $permission->sourceAuthorizable()->getAuthName());
    }

    public function testGetTargetAuthorizable(): void
    {
        $permission = new Permission();
        $permission->target_id = "1";
        $permission->target = 'target';
        $this->assertSame($permission->target_id, $permission->targetAuthorizable()->getId());
        $this->assertSame($permission->target, $permission->targetAuthorizable()->getAuthName());
    }

    public function testGetGrant(): void
    {
        $permission = new Permission();
        $permission->source_id = "1";
        $permission->source = 'source';
        $permission->target_id = "2";
        $permission->target = 'target';
        $permission->permission = 'permmm';

        $this->assertSame($permission->source_id, $permission->getGrant()->getSource()->getId());
        $this->assertSame($permission->source, $permission->getGrant()->getSource()->getAuthName());
        $this->assertSame($permission->target_id, $permission->getGrant()->getTarget()->getId());
        $this->assertSame($permission->target, $permission->getGrant()->getTarget()->getAuthName());

        $this->assertSame($permission->permission, $permission->getGrant()->getPermission());
    }

    public function testPermissionLabels(): void
    {
        $this->assertNotEmpty(Permission::permissionLabels());
    }

    public function testCheckImplicitAccessRequestGrantedJobAfterInsert()
    {
        $jobQueueMock =
            $this->getMockBuilder(JobQueueInterface::class)
                ->onlyMethods(['putJob'])
                ->getMock();
        $jobQueueMock->expects($this->once())
            ->method('putJob')
            ->with($this->isInstanceOf(CheckImplicitAccessRequestGrantedJob::class));

        \Yii::$container->set(JobQueueInterface::class, $jobQueueMock);

        $project = new Project();
        $project->title = 'Test project';
        $project->base_survey_eid = 12345;
        $project->save();

        $permission = new Permission([
            'source' => User::class,
            'source_id' => TEST_USER_ID,
            'target' => $project::class,
            'target_id' => $project->id,
            'permission' => Permission::PERMISSION_WRITE,
        ]);
        $this->assertTrue($permission->save());
    }
}
