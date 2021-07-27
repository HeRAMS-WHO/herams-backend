<?php
declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Stub\Expected;
use prime\components\View;
use prime\controllers\workspace\Facilities;
use prime\controllers\WorkspaceController;
use prime\interfaces\WorkspaceForTabMenu;
use prime\models\project\ProjectForBreadcrumb;
use prime\models\workspace\WorkspaceForBreadcrumb;
use prime\objects\BreadcrumbCollection;
use prime\repositories\ProjectRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\web\Request;

/**
 * @covers \prime\controllers\WorkspaceController
 */
class WorkspaceControllerTest extends ControllerTest
{
    public function testRenderInsertsModel(): void
    {
        $projectId = 23456;
        $workspaceForBreadcrumb = $this->getMockBuilder(WorkspaceForBreadcrumb::class)->disableOriginalConstructor()->getMock();
        $workspaceForBreadcrumb->expects($this->once())
            ->method('getProjectId')
            ->willReturn(new ProjectId($projectId));
        $workspaceRepository = $this->make(WorkspaceRepository::class, [
            'retrieveForTabMenu' => Expected::once(function (WorkspaceId $id) {
                $this->assertSame(12345, $id->getValue());

                return $this->makeEmpty(WorkspaceForTabMenu::class);
            }),
            'retrieveForBreadcrumb' => Expected::once(function (WorkspaceId $id) use ($workspaceForBreadcrumb) {
                $this->assertSame(12345, $id->getValue());

                return $workspaceForBreadcrumb;
            })
        ]);
        $projectRepository = $this->getMockBuilder(ProjectRepository::class)->disableOriginalConstructor()->getMock();
        $projectRepository->expects($this->once())
            ->method('retrieveForBreadcrumb')
            ->with(new ProjectId($projectId))
            ->willReturn($this->getMockBuilder(ProjectForBreadcrumb::class)->disableOriginalConstructor()->getMock());
        $action = $this->getMockBuilder(Facilities::class)->disableOriginalConstructor()->getMock();
        $controller = new WorkspaceController('test', \Yii::$app, $projectRepository, $workspaceRepository);
        $controller->action = $action;
        $controller->ensureBehaviors();
        $breadcrumbCollection = $this->getMockBuilder(BreadcrumbCollection::class)->getMock();
        $view = $this->make(View::class, [
            'render' => Expected::once(function ($viewName, $params) {
                $this->assertArrayHasKey('tabMenuModel', $params);
                return 'renderresult';
            }),
            'getBreadcrumbCollection' => Expected::atLeastOnce(fn() => $breadcrumbCollection),
        ]);
        $controller->setView($view);
        $controller->layout = false;
        $controller->request = new Request([
            'queryParams' => ['id' => '12345']
        ]);

        $this->assertSame('renderresult', $controller->render('test', []));
    }
}
