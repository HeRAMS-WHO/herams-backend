<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\userRole\Create;
use herams\api\controllers\userRole\Delete;
use herams\api\controllers\userRole\Index;
use herams\api\controllers\userRole\Workspace;

class UserRoleController extends \yii\rest\Controller
{
    public function actions()
    {
        return [
            'create' => Create::class,
            'index' => Index::class,
            'workspace' => Workspace::class,
            'delete' => Delete::class,
        ];
    }
}
