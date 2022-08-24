<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\facility\Create;
use prime\modules\Api\controllers\facility\ValidateNew;
use prime\modules\Api\controllers\facility\View;

class FacilityController extends Controller
{

    public function actions()
    {
        return [
            'create' => Create::class,
            'view' => View::class,
            'validate' => ValidateNew::class,
        ];
    }
}
