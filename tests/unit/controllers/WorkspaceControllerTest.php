<?php
declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Stub\Expected;
use prime\components\View;
use prime\controllers\WorkspaceController;
use prime\interfaces\WorkspaceForTabMenu;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\web\Request;

/**
 * @covers \prime\controllers\WorkspaceController
 */
class WorkspaceControllerTest extends ControllerTest
{
    public function testRenderInsertsModel()
    {
        $repo = $this->make(WorkspaceRepository::class, [
            'retrieveForTabMenu' => Expected::once(function(WorkspaceId $id) {
                $this->assertSame(12345, $id->getValue());

                return $this->makeEmpty(WorkspaceForTabMenu::class);
            })
        ]);
        $controller = new WorkspaceController('test', \Yii::$app, $repo);
        $controller->ensureBehaviors();
        $view = $this->make(View::class, [
            'render' => Expected::once(function($viewName, $params) {
                $this->assertArrayHasKey('tabMenuModel', $params);
                return 'renderresult';
            })
        ]);
        $controller->setView($view);
        $controller->layout = false;
        $controller->request = new Request([
            'queryParams' => ['id' => '12345']
        ]);

        $this->assertSame('renderresult', $controller->render('test', []));

    }

}
