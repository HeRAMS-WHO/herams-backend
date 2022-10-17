<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\models\ar\Workspace;
use prime\modules\Api\actions\DeleteAction;
use prime\modules\Api\actions\UserPermissions;
use prime\modules\Api\controllers\workspace\Create;
use prime\modules\Api\controllers\workspace\Facilities;
use prime\modules\Api\controllers\workspace\Update;
use prime\modules\Api\controllers\workspace\Validate;
use prime\modules\Api\controllers\workspace\View;

final class WorkspaceController extends Controller
{
    public function actions()
    {
        return [
            'create' => Create::class,
            'view' => View::class,
            'validate' => Validate::class,
            'facilities' => Facilities::class,
            'update' => Update::class,
            'permissions' => [
                'class' => UserPermissions::class,
                'target' => Workspace::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Workspace::find(),
            ],
        ];
    }
}
