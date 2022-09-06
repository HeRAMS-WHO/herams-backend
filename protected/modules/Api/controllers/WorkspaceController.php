<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\models\ar\Workspace;
use prime\modules\Api\actions\DeleteAction;
use prime\modules\Api\controllers\workspace\Create;
use prime\modules\Api\controllers\workspace\Facilities;
use prime\modules\Api\controllers\workspace\Refresh;
use prime\modules\Api\controllers\workspace\Update;
use prime\modules\Api\controllers\workspace\Validate;
use prime\modules\Api\controllers\workspace\View;

class WorkspaceController extends Controller
{
    public function actions()
    {
        return [
            'refresh' => Refresh::class,
            'create' => Create::class,
            'view' => View::class,
            'validate' => Validate::class,
            'facilities' => Facilities::class,
            'update' => Update::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Workspace::find(),
            ],
        ];
    }
}
