<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use prime\components\View;
use prime\controllers\element\Create;
use prime\controllers\element\Preview;
use prime\controllers\ElementController;
use prime\controllers\response\Update;
use prime\controllers\ResponseController;
use prime\models\element\ElementForBreadcrumb;
use prime\models\pages\PageForBreadcrumb;
use prime\models\project\ProjectForBreadcrumb;
use prime\models\response\ResponseForBreadcrumb;
use prime\models\workspace\WorkspaceForBreadcrumb;
use prime\objects\BreadcrumbCollection;
use prime\repositories\ProjectRepository;
use prime\repositories\ResponseRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ElementId;
use prime\values\PageId;
use prime\values\ProjectId;
use prime\values\ResponseId;
use prime\values\WorkspaceId;
use yii\web\Request;

/**
 * @covers \prime\controllers\ResponseController
 */
class ResponsesControllerTest extends Unit
{
    private BreadcrumbCollection|MockObject $breadcrumbCollection;
    private ProjectRepository|MockObject $projectRepository;
    private string $renderResult = 'testRender';
    private ResponseRepository|MockObject $responseRepository;
    private View|MockObject $view;
    private WorkspaceRepository|MockObject $workspaceRepository;

    protected function prepareController(): ResponseController
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
        $this->projectRepository = $this->getMockBuilder(ProjectRepository::class)->disableOriginalConstructor()->getMock();
        $this->responseRepository = $this->getMockBuilder(ResponseRepository::class)->disableOriginalConstructor()->getMock();
        $this->workspaceRepository = $this->getMockBuilder(WorkspaceRepository::class)->disableOriginalConstructor()->getMock();
        $controller = new ResponseController('test', \Yii::$app, $this->projectRepository, $this->responseRepository, $this->workspaceRepository);
        $controller->setView($this->view);
        $controller->ensureBehaviors();
        return $controller;
    }

    public function testRenderInsertsBreadcrumbsOnId(): void
    {
        $responseId = 12345;
        $workspaceId = 23456;
        $workspaceIdObject = new WorkspaceId($workspaceId);
        $projectId = 34567;
        $projectIdObject = new ProjectId($projectId);
        $controller = $this->prepareController();
        $response = $this->getMockBuilder(ResponseForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $response->expects($this->once())
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
        $this->responseRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with(new ResponseId($responseId))
            ->willReturn($response);
        $action = $this->getMockBuilder(Update::class)->disableOriginalConstructor()->getMock();
        $action->id = 'update';
        $controller->action = $action;
        $controller->layout = false;
        $controller->request = new Request([
            'queryParams' => ['id' => $responseId],
        ]);
        $this->assertSame($this->renderResult, $controller->render('test', []));
    }
}
