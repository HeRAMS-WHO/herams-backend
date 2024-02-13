<?php

declare(strict_types=1);

namespace prime\actions;

use prime\components\Controller;
use yii\base\Action;

/**
 * Action that renders a view
 */
abstract class FrontendAction extends Action
{
    private function getController(): Controller
    {
        assert($this->controller instanceof Controller);
        return $this->controller;
    }

    final protected function render(string $view, array $params = []): string
    {
        return $this->getController()->render($view, $params);
    }
}
