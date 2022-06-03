<?php

declare(strict_types=1);

namespace prime\tests\unit\models\workspace;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Project;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\workspace\WorkspaceForTabMenu;
use prime\tests\_helpers\AllFunctionsMustHaveReturnTypes;
use yii\base\BaseObject;

/**
 * @covers \prime\models\workspace\WorkspaceForTabMenu
 */
class WorkspaceForTabMenuTest extends Unit
{
    use AllFunctionsMustHaveReturnTypes;

    private function getModel(): WorkspaceForTabMenu
    {
        $workspace = new WorkspaceForLimesurvey();
        $workspace->id = 15;
        $workspace->project_id = 123;
        $workspace->permissionSourceCount = 5;
        $workspace->facilityCount = 1244;
        $workspace->title = 'workspace title';
        $workspace->responseCount = 1233;
        $workspace->populateRelation('project', new Project([
            'title' => 'project title',
        ]));
        $workspace->isNewRecord = false;
        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->getMock();
        return new WorkspaceForTabMenu($accessChecker, $workspace);
    }

    public function testExceptionIfRecordIsNew(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new WorkspaceForTabMenu($this->getMockBuilder(AccessCheckInterface::class)->getMock(), new WorkspaceForLimesurvey());
    }

    public function testGetId(): void
    {
        $this->assertSame(15, $this->getModel()->id()->getValue());
    }

    public function testGetTitle(): void
    {
        $this->assertSame('workspace title', $this->getModel()->title());
    }

    public function testGetFacilityCount(): void
    {
        $this->assertSame(1244, $this->getModel()->getFacilityCount());
    }

    public function testGetResponseCount(): void
    {
        $this->assertSame(1233, $this->getModel()->getResponseCount());
    }

    public function testGetProjectId(): void
    {
        $this->assertSame(123, $this->getModel()->projectId()->getValue());
    }

    public function testGetPermissionSourceCount(): void
    {
        $this->assertSame(5, $this->getModel()->getPermissionSourceCount());
    }

    public function testGetProjectTitle(): void
    {
        $this->assertSame('project title', $this->getModel()->projectTitle());
    }

    public function testCanCurrentUser(): void
    {
        $workspace = new WorkspaceForLimesurvey();
        $workspace->id = 121333;
        $workspace->project_id = 1;
        $workspace->isNewRecord = false;

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->getMock();
        $accessChecker->expects($this->once())
            ->method('checkPermission')
            ->willReturnCallback(function (WorkspaceForLimesurvey $model, string $permission) use ($workspace): bool {
                $this->assertSame($workspace, $model);
                $this->assertSame('test', $permission);
                return false;
            });

        $workspaceForTabMenu = new WorkspaceForTabMenu($accessChecker, $workspace);
        $this->assertFalse($workspaceForTabMenu->canCurrentUser('test'));
    }
}
