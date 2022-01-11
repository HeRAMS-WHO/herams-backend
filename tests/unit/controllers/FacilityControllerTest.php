<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use prime\components\View;
use prime\controllers\facility\Create;
use prime\controllers\facility\Responses;
use prime\controllers\FacilityController;
use prime\models\facility\FacilityForBreadcrumb;
use prime\models\project\ProjectForBreadcrumb;
use prime\models\workspace\WorkspaceForBreadcrumb;
use prime\objects\BreadcrumbCollection;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\web\Request;

/**
 * @covers \prime\controllers\FacilityController
 */
class FacilityControllerTest extends Unit
{
    private BreadcrumbCollection|MockObject $breadcrumbCollection;
    private FacilityRepository|MockObject $facilityRepository;
    private ProjectRepository|MockObject $projectRepository;
    private string $renderResult = 'testRender';
    private View|MockObject $view;
    private WorkspaceRepository|MockObject $workspaceRepository;

    protected function prepareController(): FacilityController
    {
        $this->breadcrumbCollection = $this->getMockBuilder(BreadcrumbCollection::class)->getMock();
        $this->view = $this->getMockBuilder(View::class)->disableOriginalConstructor()->getMock();
        $this->view->expects($this->once())
            ->method('getBreadcrumbCollection')
            ->willReturn($this->breadcrumbCollection)
        ;
        $this->view->expects($this->once())
            ->method('render')
            ->willReturn($this->renderResult);
        $this->facilityRepository = $this->getMockBuilder(FacilityRepository::class)->disableOriginalConstructor()->getMock();
        $this->projectRepository = $this->getMockBuilder(ProjectRepository::class)->disableOriginalConstructor()->getMock();
        $this->workspaceRepository = $this->getMockBuilder(WorkspaceRepository::class)->disableOriginalConstructor()->getMock();
        $controller = new FacilityController('test', \Yii::$app, $this->facilityRepository, $this->projectRepository, $this->workspaceRepository);
        $controller->setView($this->view);
        $controller->ensureBehaviors();
        return $controller;
    }

    public function testRenderInsertsBreadcrumbsCreate(): void
    {
        $projectId = 12345;
        $workspaceId = 23456;
        $controller = $this->prepareController();
        $projectIdObject = new ProjectId($projectId);
        $workspace = $this->getMockBuilder(WorkspaceForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $workspace->expects($this->once())
            ->method('getProjectId')
            ->willReturn($projectIdObject);
        $project = $this->getMockBuilder(ProjectForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $this->breadcrumbCollection->expects($this->exactly(3))
            ->method('add')
            ->willReturnSelf();
        $this->workspaceRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with(new WorkspaceId($workspaceId))
            ->willReturn($workspace);
        $this->projectRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with($projectIdObject)
            ->willReturn($project);
        $action = $this->getMockBuilder(Create::class)->disableOriginalConstructor()->getMock();
        $action->id = 'create';
        $controller->action = $action;
        $controller->layout = false;
        $controller->request = new Request([
            'queryParams' => ['workspaceId' => $workspaceId],
        ]);
        $this->assertSame($this->renderResult, $controller->render('test', []));
    }

    public function testRenderInsertsBreadcrumbsOnId(): void
    {
        $facilityId = (string) 34567;
        $workspaceId = 12345;
        $workspaceIdObject = new WorkspaceId($workspaceId);
        $projectId = 23456;
        $projectIdObject = new ProjectId($projectId);
        $controller = $this->prepareController();
        $facility = $this->getMockBuilder(FacilityForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $facility->expects($this->once())
            ->method('getWorkspaceId')
            ->willReturn($workspaceIdObject);
        $project = $this->getMockBuilder(ProjectForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $workspace = $this->getMockBuilder(WorkspaceForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $workspace->expects($this->once())
            ->method('getProjectId')
            ->willReturn($projectIdObject);
        $this->breadcrumbCollection->expects($this->exactly(3))
            ->method('add')
            ->willReturnSelf();
        $this->workspaceRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with($workspaceIdObject)
            ->willReturn($workspace);
        $this->projectRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with($projectIdObject)
            ->willReturn($project);
        $this->facilityRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with(new FacilityId($facilityId))
            ->willReturn($facility);
        $action = $this->getMockBuilder(Responses::class)->disableOriginalConstructor()->getMock();
        $action->id = 'responses';
        $controller->action = $action;
        $controller->layout = false;
        $controller->request = new Request([
            'queryParams' => ['id' => $facilityId],
        ]);
        $this->assertSame($this->renderResult, $controller->render('test', []));
    }
}
