<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Workspace;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

/**
 * @covers \prime\repositories\WorkspaceRepository
 */
class WorkspaceRepositoryTest extends Unit
{
    public function testFindForBreadcrumb(): void
    {
        $model = new Workspace([
            'title' => 'Test workspace',
            'token' => '12345',
            'project_id' => 1,
        ]);
        $this->assertTrue($model->save());

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();

        $repository = new WorkspaceRepository($accessChecker, $modelHydrator);
        $breadcrumb = $repository->retrieveForBreadcrumb(new WorkspaceId($model->id));

        $this->assertEquals($model->title, $breadcrumb->getLabel());
        $this->assertEquals(['/workspace/facilities', 'id' => $model->id], $breadcrumb->getUrl());
        $this->assertEquals(new ProjectId($model->project_id), $breadcrumb->getProjectId());
    }
}
