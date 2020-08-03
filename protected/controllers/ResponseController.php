<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\response\Compare;

class ResponseController extends Controller
{
    public $layout = 'admin-content';
    public function actions(): array
    {
        return [
            'compare' => Compare::class
        ];
    }
}
