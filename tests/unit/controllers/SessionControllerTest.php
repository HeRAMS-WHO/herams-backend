<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use prime\controllers\SessionController;
use prime\helpers\JobQueueProxy;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\User;

/**
 * @covers \prime\controllers\SessionController
 */
final class SessionControllerTest extends ControllerTest
{
    public function testConstructor(): void
    {
        $controller = $this->getController();
        $this->assertInstanceOf(SessionController::class, $controller);
    }

    public function testCreateAction(): void
    {
        $controller = $this->getController();
        $createAction = $controller->createAction('create');
        $this->assertTrue($controller->beforeAction($createAction));
    }


    public function testGuestCannotDelete(): void
    {
        $isGuest = Yii::$app->user->isGuest;
        $this->assertTrue($isGuest);
        $controller = $this->getController();
        $accessControl = $controller->getBehavior('access');
        $accessControl->user = false;
        $deleteAction = $controller->createAction('delete');
        $this->expectException(InvalidConfigException::class);
        $this->assertFalse($controller->beforeAction($deleteAction));
    }

    public function testUserCanDelete(): void
    {
        $email = 'test@test.com';
        $id = 12345;
        $name = 'Test user';

        $user = new \prime\models\ar\User();
        $user->email = $email;
        $user->id = $id;
        $user->name = $name;

        Yii::$app->user->login($user);

        $isGuest = Yii::$app->user->isGuest;
        $this->assertFalse($isGuest);

        $controller = $this->getController();
        $accessControl = $controller->getBehavior('access');
        $accessControl->user = Yii::$app->user;
        $deleteAction = $controller->createAction('delete');
        $this->assertTrue($controller->beforeAction($deleteAction));
    }
}
