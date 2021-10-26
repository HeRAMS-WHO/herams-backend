<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Stub\Expected;
use prime\components\View;
use prime\controllers\site\Maintenance;
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
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\Request;

/**
 * @covers \prime\controllers\SiteController
 */
class SiteControllerTest extends ControllerTest
{

    public function testMaintenancePageWithoutUser()
    {
        $controller = $this->getController();
        $accessControl = $controller->getBehavior('access');
        $this->assertInstanceOf(AccessControl::class, $accessControl);
        $rule = $accessControl->rules[0];
        $this->assertInstanceOf(AccessRule::class, $rule);
        $this->assertTrue($rule->allows($controller->createAction('maintenance'), false, new Request()));
    }
}
