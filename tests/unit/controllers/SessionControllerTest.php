<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use prime\controllers\SessionController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\Request;

/**
 * @covers \prime\controllers\SessionController
 */
class SessionControllerTest extends ControllerTest
{


    // tests
    public function testSessionCreate()
    {
        $controller = $this->getController(); // new SessionController('test', \Yii::$app);
        $this->assertInstanceOf(SessionController::class, $controller);

        $accessControl = $controller->getBehavior('access');
        $this->assertInstanceOf(AccessControl::class, $accessControl);
        $rule = $accessControl->rules[0];
        $this->assertInstanceOf(AccessRule::class, $rule);
        //$this->assertTrue($rule->allows($controller->createAction('create'), false, new Request()));

    }

    // tests
    public function testSessionDelete()
    {
        $controller = $this->getController(); // new SessionController('test', \Yii::$app);
        $this->assertInstanceOf(SessionController::class, $controller);

        $accessControl = $controller->getBehavior('access');
        $this->assertInstanceOf(AccessControl::class, $accessControl);
        $rule = $accessControl->rules[1];
        $this->assertInstanceOf(AccessRule::class, $rule);
        //$this->assertTrue($rule->allows($controller->createAction('delete'), false, new Request()));

    }

}