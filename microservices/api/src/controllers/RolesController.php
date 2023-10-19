<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\roles\Delete;
use herams\api\controllers\roles\Index;
use herams\api\controllers\roles\Permissions;
use herams\api\controllers\roles\Update;
use herams\api\controllers\roles\View;

final class RolesController extends Controller
{
    public function actions()
    {
        return [
            'index' => Index::class,
            'view' => View::class,
            'permissions' => Permissions::class,
            'update' => Update::class,
            'delete' => Delete::class,
        ];
    }
}
