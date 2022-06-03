<?php

declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Test\Unit;
use yii\base\Action;
use yii\base\Controller;
use yii\db\ActiveRecord;

/**
 * @coversNothing
 */
abstract class ControllerTest extends Unit
{
    final protected function getController(): Controller
    {
        $class = strtr(get_class($this), [
            __NAMESPACE__ => 'prime\controllers',
            'Test' => '',
        ]);
        return \Yii::$container->get($class, ['test', \Yii::$app]);
    }

    public function testActions()
    {
        $controller = $this->getController();
        foreach ($controller->actions() as $id => $definition) {
            $this->assertInstanceOf(Action::class, $controller->createAction($id));
        }
    }
}
