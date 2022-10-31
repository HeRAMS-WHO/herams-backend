<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\actions\UserPermissions;
use herams\api\controllers\project\Create;
use herams\api\controllers\project\Index;
use herams\api\controllers\project\LatestData;
use herams\api\controllers\project\Locales;
use herams\api\controllers\project\Pages;
use herams\api\controllers\project\Summary;
use herams\api\controllers\project\Update;
use herams\api\controllers\project\Validate;
use herams\api\controllers\project\Variables;
use herams\api\controllers\project\View;
use herams\api\controllers\project\Workspaces;
use prime\models\ar\Project;

class ProjectController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

//        $behaviors['access']['rules'] = [
//            [
//                'allow' => true,
//                'actions' => ['index', 'summary'],
//            ],
//            ...$behaviors['access']['rules'],
//        ];
        return $behaviors;
    }

    public function actions(): array
    {
        return [
            'index' => Index::class,
            'summary' => Summary::class,
            'variables' => Variables::class,
            'latest-data' => LatestData::class,
            'validate' => Validate::class,
            'create' => Create::class,
            'update' => Update::class,
            'view' => View::class,
            'locales' => Locales::class,
            'pages' => Pages::class,
            'workspaces' => Workspaces::class,
            'permissions' => [
                'class' => UserPermissions::class,
                'target' => Project::class,
            ],
        ];
    }
}
