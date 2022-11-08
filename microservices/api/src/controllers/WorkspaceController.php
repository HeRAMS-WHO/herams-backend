<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\actions\DeleteAction;
use herams\api\actions\UserPermissions;
use herams\api\controllers\workspace\Create;
use herams\api\controllers\workspace\Facilities;
use herams\api\controllers\workspace\Update;
use herams\api\controllers\workspace\Validate;
use herams\api\controllers\workspace\View;
use herams\common\models\Workspace;

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
