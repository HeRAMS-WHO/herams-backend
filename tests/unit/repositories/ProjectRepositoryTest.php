<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use herams\common\domain\project\ProjectRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\PermissionOld;
use herams\common\models\Project;
use herams\common\values\ProjectId;
use prime\modules\Api\models\UpdateProject;

/**
 * @covers \herams\common\domain\project\ProjectRepository
 */
class ProjectRepositoryTest extends Unit
{
    public function testFindForBreadcrumb(): void
    {
        $project = Project::findOne([
            'id' => 1,
        ]);

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $activeRecordHydrator = $this->getMockBuilder(ActiveRecordHydratorInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();

        $projectRepository = new ProjectRepository($accessChecker, $activeRecordHydrator, $modelHydrator);
        $breadcrumb = $projectRepository->retrieveForBreadcrumb(new ProjectId($project->id));

        $this->assertEquals($project->title, $breadcrumb->getLabel());
        $this->assertEquals([
            '/project/view',
            'id' => $project->id,
        ], $breadcrumb->getUrl());
    }

    public function testRetrieveForUpdate(): void
    {
        $project = Project::findOne([
            'id' => 1,
        ]);

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();

        // Test that permission is checked
        $accessChecker->expects($this->once())->method('requirePermission')->willReturnCallback(function ($subject, $permission) use ($project) {
            $this->assertTrue($project->equals($subject));
            $this->assertSame(PermissionOld::PERMISSION_WRITE, $permission);
        });

        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();
        // Test that hydrator is called
        $modelHydrator->expects($this->once())->method('hydrateFromActiveRecord');
        $activeRecordHydrator = $this->getMockBuilder(ActiveRecordHydratorInterface::class)->disableOriginalConstructor()->getMock();
        $projectRepository = new ProjectRepository($accessChecker, $activeRecordHydrator, $modelHydrator);

        $retrieved = $projectRepository->retrieveForUpdate(new ProjectId($project->id));
        $this->assertInstanceOf(UpdateProject::class, $retrieved);
        // Test that scenario is set
        $this->assertNotSame(UpdateProject::SCENARIO_DEFAULT, $retrieved->getScenario());
    }
}
