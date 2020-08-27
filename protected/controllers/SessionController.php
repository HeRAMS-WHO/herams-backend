<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\session\Create;
use prime\controllers\session\Delete;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SessionController extends Controller
{
    public $layout = 'map-popover-session';
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => 'true',
                            'actions' => ['create']
                        ],
                        [
                            'allow' => 'true',
                            'actions' => ['delete'],
                            'roles' => ['@']
                        ]
                    ]
                ]
            ]
        );
    }

    public function actions()
    {
        return [
            'create' => Create::class,
            'delete' => Delete::class
        ];
    }
}
