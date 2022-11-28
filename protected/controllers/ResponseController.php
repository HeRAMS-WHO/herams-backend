<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\response\View;

class ResponseController extends Controller
{
    public $layout = Controller::LAYOUT_ADMIN_TABS;

    public function actions(): array
    {
        return [
            'view' => View::class,
        ];
    }
}
