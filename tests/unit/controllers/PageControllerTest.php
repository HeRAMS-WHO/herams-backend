<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use prime\components\View;
use prime\controllers\facility\Responses;
use prime\controllers\page\Create;
use prime\controllers\page\Update;
use prime\controllers\PageController;
use prime\models\pages\PageForBreadcrumb;
use prime\models\project\ProjectForBreadcrumb;
use prime\objects\BreadcrumbCollection;
use prime\repositories\PageRepository;
use prime\repositories\ProjectRepository;
use prime\values\PageId;
use prime\values\ProjectId;
use yii\web\Request;

/**
 * @covers \prime\controllers\PageController
 */
class PageControllerTest extends Unit
{
    private BreadcrumbCollection|MockObject $breadcrumbCollection;

    private PageRepository|MockObject $pageRepository;

    private ProjectRepository|MockObject $projectRepository;

    private string $renderResult = 'testRender';

    private View|MockObject $view;

    protected function prepareController(): PageController
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
        $this->pageRepository = $this->getMockBuilder(PageRepository::class)->disableOriginalConstructor()->getMock();
        $this->projectRepository = $this->getMockBuilder(ProjectRepository::class)->disableOriginalConstructor()->getMock();
        $controller = new PageController('test', \Yii::$app, $this->pageRepository, $this->projectRepository);
        $controller->setView($this->view);
        $controller->ensureBehaviors();
        return $controller;
    }

    public function testRenderInsertsBreadcrumbsCreate(): void
    {
        $projectId = 12345;
        $controller = $this->prepareController();
        $projectIdObject = new ProjectId($projectId);
        $project = $this->getMockBuilder(ProjectForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $this->breadcrumbCollection->expects($this->exactly(2))
            ->method('add')
            ->willReturnSelf();
        $this->projectRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with($projectIdObject)
            ->willReturn($project);
        $action = $this->getMockBuilder(Create::class)->disableOriginalConstructor()->getMock();
        $action->id = 'create';
        $controller->action = $action;
        $controller->layout = false;
        $controller->request = new Request([
            'queryParams' => [
                'project_id' => $projectId,
            ],
        ]);
        $this->assertSame($this->renderResult, $controller->render('test', []));
    }

    public function testRenderInsertsBreadcrumbsOnId(): void
    {
        $pageId = 12345;
        $projectId = 23456;
        $projectIdObject = new ProjectId($projectId);
        $controller = $this->prepareController();
        $page = $this->getMockBuilder(PageForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $page->expects($this->once())
            ->method('getProjectId')
            ->willReturn($projectIdObject);
        $project = $this->getMockBuilder(ProjectForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $this->breadcrumbCollection->expects($this->exactly(2))
            ->method('add')
            ->willReturnSelf();
        $this->projectRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with($projectIdObject)
            ->willReturn($project);
        $this->pageRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with(new PageId($pageId))
            ->willReturn($page);
        $action = $this->getMockBuilder(Update::class)->disableOriginalConstructor()->getMock();
        $action->id = 'update';
        $controller->action = $action;
        $controller->layout = false;
        $controller->request = new Request([
            'queryParams' => [
                'id' => $pageId,
            ],
        ]);
        $this->assertSame($this->renderResult, $controller->render('test', []));
    }
}
