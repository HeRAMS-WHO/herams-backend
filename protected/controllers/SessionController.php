<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\session\AuthToken;
use prime\controllers\session\Create;
use prime\controllers\session\Delete;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SessionController extends Controller
{
    public $layout = 'map-popover-session';

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => 'true',
                            'actions' => ['create'],
                        ],
                        [
                            'allow' => 'true',
                            'actions' => ['delete', 'auth-token'],
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function actions()
    {
        return [
            'create' => Create::class,
            'delete' => Delete::class,
            'auth-token' => AuthToken::class
        ];
    }
}
