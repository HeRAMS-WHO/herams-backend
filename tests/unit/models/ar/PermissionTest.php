<?php
declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;

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
