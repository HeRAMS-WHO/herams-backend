<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\permissions\Index;

final class PermissionsController extends Controller
{
    public function actions()
    {
        return [
            'index' => Index::class,
        ];
    }
}
