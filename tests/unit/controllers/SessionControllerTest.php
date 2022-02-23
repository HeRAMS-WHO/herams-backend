<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use prime\controllers\SessionController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\Request;
use yii\web\User;

/**
 * @covers \prime\controllers\SessionController
 */
final class SessionControllerTest extends ControllerTest
{
    public function testSessionRules(): void
    {
        $controller = $this->getController(); // new controller instance
        $this->assertInstanceOf(SessionController::class, $controller); //test instance

        //get access behaviour and test that the rules exist
        $accessControl = $controller->getBehavior('access');
        $this->assertInstanceOf(AccessControl::class, $accessControl);

git         $rule = $accessControl->rules[0];
        $this->assertInstanceOf(AccessRule::class, $rule);

        $rule = $accessControl->rules[1];
        $this->assertInstanceOf(AccessRule::class, $rule);

        #test create action
        $this->assertTrue($rule->allows($controller->createAction('create'), false, new Request()));
    }
}
