<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\user\Index;
use herams\api\controllers\user\View;
use herams\api\controllers\user\Workspaces;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class UserController extends \yii\rest\Controller
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'workspaces' => Workspaces::class,
            'view' => View::class,
            'index' => Index::class,
        ];
    }
}
