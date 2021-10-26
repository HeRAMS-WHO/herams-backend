<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Project;
use prime\repositories\ProjectRepository;
use prime\values\ProjectId;

/**
 * @covers \prime\repositories\ProjectRepository
 */
class ProjectRepositoryTest extends Unit
{
    public function testFindForBreadcrumb(): void
    {
        $project = Project::findOne(['id' => 1]);

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();

        $projectRepository = new ProjectRepository($accessChecker, $modelHydrator);
        $breadcrumb = $projectRepository->retrieveForBreadcrumb(new ProjectId($project->id));

        $this->assertEquals($project->title, $breadcrumb->getLabel());
        $this->assertEquals(['/project/view', 'id' => $project->id], $breadcrumb->getUrl());
    }
}
