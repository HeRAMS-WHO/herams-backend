<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Test\Unit;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use PHPUnit\Framework\MockObject\MockObject;
use prime\components\View;
use prime\controllers\FacilityController;
use prime\objects\BreadcrumbCollection;

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
}
