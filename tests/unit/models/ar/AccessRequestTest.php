<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\jobs\accessRequests\CreatedNotificationJob;
use prime\models\ar\AccessRequest;
use prime\models\ar\Project;
use prime\tests\FunctionalTester;

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

    /**
     * @todo Move this to a functional test; it is not a unit test
     * @todo Refactor underlying code to not require changing the global container (this is not restored for every test)
     *
     */
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
        $this->assertTrue($project->save());

        $accessRequest = new AccessRequest([
            'subject' => 'test',
            'body' => 'test',
            'target' => $project,
            'permissions' => [AccessRequest::PERMISSION_WRITE],
            'created_by' => TEST_USER_ID,
        ]);
        $this->assertTrue($accessRequest->save());
    }

    public function testModelHasExpirationDate(): void
    {
        $accessRequest = new AccessRequest();
        $this->assertNotNull($accessRequest->expires_at);
    }

    public function testPopulateClearsDefaults(): void
    {
        $accessRequest = new AccessRequest();

        AccessRequest::populateRecord($accessRequest, [
            'id' => 12345
        ]);
        $this->assertNull($accessRequest->expires_at);
    }
}
