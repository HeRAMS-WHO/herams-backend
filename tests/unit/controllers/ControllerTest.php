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
    /**
     * @return ActiveRecord
     */
    final protected function getController(): Controller
    {
        $class = strtr(get_class($this), [
            __NAMESPACE__ => 'prime\controllers',
            'Test' => ''
        ]);
        return new $class('controllerid', null);
    }

    public function testActions()
    {
        $controller = $this->getController();
        foreach ($controller->actions() as $action) {
            $class = is_string($action) ? $action : $action['class'];
            $this->assertTrue(class_exists($class));
            $this->assertTrue(is_subclass_of($class, Action::class, true));
        }
    }
}
