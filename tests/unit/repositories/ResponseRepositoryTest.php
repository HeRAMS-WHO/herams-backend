<?php
declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use prime\repositories\ResponseRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ResponseId;
use prime\values\WorkspaceId;
use yii\db\Expression;

/**
 * @covers \prime\repositories\ResponseRepository
 */
class ResponseRepositoryTest extends Unit
{
    public function testFindForBreadcrumb(): void
    {
        $workspace = new Workspace([
            'title' => 'Test workspace',
            'token' => '12345',
            'tool_id' => 1,
        ]);
        $this->assertTrue($workspace->save());
        $response = new Response([
            'date' => new Expression('NOW()'),
            'id' => 1,
            'hf_id' => 1,
            'survey_id' => 391149,
            'workspace_id' => $workspace->id,
        ]);
        $this->assertTrue($response->save());

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();
        $workspaceRepository = $this->getMockBuilder(WorkspaceRepository::class)->disableOriginalConstructor()->getMock();

        $repository = new ResponseRepository($accessChecker, $modelHydrator, $workspaceRepository);
        $breadcrumb = $repository->retrieveForBreadcrumb(new ResponseId($response->id));

        $this->assertEquals($response->getName(), $breadcrumb->getLabel());
        $this->assertEquals(['/response/compare', 'id' => $response->id], $breadcrumb->getUrl());
        $this->assertEquals(new WorkspaceId($response->workspace_id), $breadcrumb->getWorkspaceId());
    }
}
