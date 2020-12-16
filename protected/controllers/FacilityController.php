<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\facility\Create;
use prime\controllers\facility\Index;
use prime\controllers\facility\Responses;

class FacilityController extends Controller
{
    public $layout = 'admin-fullwidth';

    public function actions(): array
    {
        return [
            'index' => Index::class,
            'create' => Create::class,
            'responses' => Responses::class
        ];
    }
}
