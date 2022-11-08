<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\facility\AdminResponses;
use herams\api\controllers\facility\Create;
use herams\api\controllers\facility\DataResponses;
use herams\api\controllers\facility\ValidateNew;
use herams\api\controllers\facility\View;

final class FacilityController extends Controller
{
    public function actions()
    {
        return [
            'create' => Create::class,
            'view' => View::class,
            'validate' => ValidateNew::class,
            'data-responses' => DataResponses::class,
            'admin-responses' => AdminResponses::class,
        ];
    }
}
