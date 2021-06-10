<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\response\Compare;
use prime\controllers\response\Update;

class ResponseController extends Controller
{
    public $layout = Controller::LAYOUT_ADMIN_TABS;

    public function actions(): array
    {
        return [
            'compare' => Compare::class,
            'update' => Update::class

        ];
    }
}
