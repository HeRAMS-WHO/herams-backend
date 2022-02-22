<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\forms\project\Update;
use prime\models\user\UserForSelect2;
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


    public function testRetrieveForUpdate(): void
    {
        $project = Project::findOne(['id' => 1]);

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();

        // Test that permission is checked
        $accessChecker->expects($this->once())->method('requirePermission')->willReturnCallback(function ($subject, $permission) use ($project) {
            $this->assertTrue($project->equals($subject));
            $this->assertSame(Permission::PERMISSION_WRITE, $permission);
        });

        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();
        // Test that hydrator is called
        $modelHydrator->expects($this->once())->method('hydrateFromActiveRecord');
        $projectRepository = new ProjectRepository($accessChecker, $modelHydrator);

        $retrieved = $projectRepository->retrieveForUpdate(new ProjectId($project->id));
        $this->assertInstanceOf(Update::class, $retrieved);
        // Test that scenario is set
        $this->assertNotSame(Update::SCENARIO_DEFAULT, $retrieved->getScenario());
    }
}
