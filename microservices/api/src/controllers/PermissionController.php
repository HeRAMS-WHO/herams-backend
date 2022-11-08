<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\permission\Create;
use herams\api\controllers\permission\Delete;

final class PermissionController extends Controller
{
    public function actions(): array
    {
        return [
            'create' => Create::class,
            'delete' => Delete::class
        ];
    }
}
