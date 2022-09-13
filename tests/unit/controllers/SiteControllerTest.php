<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

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
