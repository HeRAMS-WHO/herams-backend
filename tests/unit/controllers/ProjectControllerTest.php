<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use prime\components\View;
use prime\controllers\project\Index;
use prime\controllers\ProjectController;
use prime\objects\BreadcrumbCollection;
use yii\web\Request;

/**
 * @covers \prime\controllers\ProjectController
 */
class ProjectControllerTest extends Unit
{
    private BreadcrumbCollection|MockObject $breadcrumbCollection;
    private string $renderResult = 'testRender';
    private View|MockObject $view;

    protected function prepareController(): ProjectController
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
        $controller = new ProjectController('test', \Yii::$app);
        $controller->setView($this->view);
        $controller->ensureBehaviors();
        return $controller;
    }

    public function testRenderInsertsBreadcrumbs(): void
    {
        $controller = $this->prepareController();
        $this->breadcrumbCollection->expects($this->exactly(1))
            ->method('add')
            ->willReturnSelf();
        $action = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $action->id = 'index';
        $controller->action = $action;
        $controller->layout = false;
        $controller->request = new Request();
        $this->assertSame($this->renderResult, $controller->render('test', []));
    }
}
