<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\components\View;
use prime\helpers\HeramsVariableSet;

interface DashboardWidgetInterface
{
    public function renderWidget(HeramsVariableSet $variableSet, View $view, iterable $data): void;
}
