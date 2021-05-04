<?php
declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\models\ar\AccessRequest;
use prime\models\ar\Project;
use prime\repositories\AccessRequestRepository;
use yii\base\InvalidArgumentException;

/**
 * @covers \prime\repositories\AccessRequestRepository
 */
class AccessRequestRepositoryTest extends Unit
{
    private function createAccessRequest(): AccessRequest
    {
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
        $accessRequest->save();

        return $accessRequest;
    }

    public function testTest()
    {
        $respository = new AccessRequestRepository();

        $this->assertEquals($respository->retrieve(1), null);
        $this->expectException(InvalidArgumentException::class);
        $respository->retrieveOrThrow(1);

        $accessRequest = $this->createAccessRequest();
        $this->assertObjectEquals($accessRequest, $respository->retrieveOrThrow($accessRequest->id));
    }
}
