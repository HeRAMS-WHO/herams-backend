<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\permission\ForTarget;
use prime\modules\Api\controllers\permission\Grant;

final class PermissionController extends Controller
{
    public function actions(): array
    {
        return [
            'grant' => Grant::class,
            'for-target' => ForTarget::class,
        ];
    }
}
