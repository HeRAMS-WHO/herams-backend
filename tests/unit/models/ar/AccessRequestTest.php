<?php
declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\jobs\accessRequests\CreatedNotificationJob;
use prime\models\ar\AccessRequest;
use prime\models\ar\Project;

/**
 * @covers \prime\models\ar\AccessRequest
 */
class AccessRequestTest extends ActiveRecordTest
{
    public function validSamples(): array
    {
        return [];
    }

    public function invalidSamples(): array
    {
        return [
            [
                []
            ]
        ];
    }

    public function testNotificationJobAfterInsert(): void
    {
        $jobQueueMock =
            $this->getMockBuilder(JobQueueInterface::class)
                ->onlyMethods(['putJob'])
                ->getMock();
        $jobQueueMock->expects($this->once())
            ->method('putJob')
            ->with($this->isInstanceOf(CreatedNotificationJob::class));

        \Yii::$container->set(JobQueueInterface::class, $jobQueueMock);

        $project = new Project();
        $project->title = 'Test project';
        $project->base_survey_eid = 12345;
        $project->save();

        $accessRequest = new AccessRequest([
            'subject' => 'test',
            'body' => 'test',
            'target' => $project,
            'permissions' => [AccessRequest::PERMISSION_WRITE],
            'created_by' => TEST_USER_ID,
        ]);
        $this->assertTrue($accessRequest->save());
    }
}
