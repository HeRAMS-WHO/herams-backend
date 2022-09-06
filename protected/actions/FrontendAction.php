<?php

declare(strict_types=1);

namespace prime\actions;

use prime\components\Controller;
use prime\components\View;
use prime\objects\BreadcrumbCollection;
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

    final protected function getBreadcrumbCollection(): BreadcrumbCollection
    {
        $view = $this->controller->view;
        assert($view instanceof View);
        return $view->getBreadcrumbCollection();
    }
}
