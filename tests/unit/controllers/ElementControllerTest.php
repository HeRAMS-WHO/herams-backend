<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use prime\components\View;
use prime\controllers\element\Create;
use prime\controllers\element\Preview;
use prime\controllers\ElementController;
use prime\models\element\ElementForBreadcrumb;
use prime\models\pages\PageForBreadcrumb;
use prime\models\project\ProjectForBreadcrumb;
use prime\objects\BreadcrumbCollection;
use prime\repositories\ElementRepository;
use prime\repositories\PageRepository;
use prime\repositories\ProjectRepository;
use prime\values\ElementId;
use prime\values\PageId;
use prime\values\ProjectId;
use yii\web\Request;

/**
 * @covers \prime\controllers\ElementController
 */
class ElementControllerTest extends Unit
{
    private BreadcrumbCollection|MockObject $breadcrumbCollection;

    private ElementRepository|MockObject $elementRepository;

    private PageRepository|MockObject $pageRepository;

    private ProjectRepository|MockObject $projectRepository;

    private string $renderResult = 'testRender';

    private View|MockObject $view;

    protected function prepareController(): ElementController
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
        $this->elementRepository = $this->getMockBuilder(ElementRepository::class)->disableOriginalConstructor()->getMock();
        $this->pageRepository = $this->getMockBuilder(PageRepository::class)->disableOriginalConstructor()->getMock();
        $this->projectRepository = $this->getMockBuilder(ProjectRepository::class)->disableOriginalConstructor()->getMock();
        $controller = new ElementController('test', \Yii::$app, $this->elementRepository, $this->pageRepository, $this->projectRepository);
        $controller->setView($this->view);
        $controller->ensureBehaviors();
        return $controller;
    }

    public function testRenderInsertsBreadcrumbsCreate(): void
    {
        $pageId = 12345;
        $projectId = 23456;
        $controller = $this->prepareController();
        $projectIdObject = new ProjectId($projectId);
        $page = $this->getMockBuilder(PageForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $page->expects($this->once())
            ->method('getProjectId')
            ->willReturn($projectIdObject);
        $project = $this->getMockBuilder(ProjectForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $this->breadcrumbCollection->expects($this->exactly(3))
            ->method('add')
            ->willReturnSelf();
        $this->pageRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with(new PageId($pageId))
            ->willReturn($page);
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
                'page_id' => $pageId,
            ],
        ]);
        $this->assertSame($this->renderResult, $controller->render('test', []));
    }

    public function testRenderInsertsBreadcrumbsOnId(): void
    {
        $elementId = 34567;
        $pageId = 12345;
        $pageIdObject = new PageId($pageId);
        $projectId = 23456;
        $projectIdObject = new ProjectId($projectId);
        $controller = $this->prepareController();
        $page = $this->getMockBuilder(PageForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $page->expects($this->once())
            ->method('getProjectId')
            ->willReturn($projectIdObject);
        $project = $this->getMockBuilder(ProjectForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $element = $this->getMockBuilder(ElementForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $element->expects($this->once())
            ->method('getPageId')
            ->willReturn($pageIdObject);
        $this->breadcrumbCollection->expects($this->exactly(3))
            ->method('add')
            ->willReturnSelf();
        $this->pageRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with($pageIdObject)
            ->willReturn($page);
        $this->projectRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with($projectIdObject)
            ->willReturn($project);
        $this->elementRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with(new ElementId($elementId))
            ->willReturn($element);
        $action = $this->getMockBuilder(Preview::class)->disableOriginalConstructor()->getMock();
        $action->id = 'preview';
        $controller->action = $action;
        $controller->layout = false;
        $controller->request = new Request([
            'queryParams' => [
                'id' => $elementId,
            ],
        ]);
        $this->assertSame($this->renderResult, $controller->render('test', []));
    }
}
