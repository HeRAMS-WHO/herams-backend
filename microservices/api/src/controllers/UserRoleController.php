<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\userRole\Create;

class UserRoleController extends Controller
{
    public function actions()
    {
        return [
            'create' => Create::class,
        ];
    }
}
