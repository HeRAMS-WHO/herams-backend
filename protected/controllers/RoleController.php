<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\role\Index;
use prime\controllers\role\Update;
use yii\helpers\ArrayHelper;

class RoleController extends Controller
{
    public $defaultAction = 'index';

    public $layout = self::LAYOUT_ADMIN;

    public function actions(): array
    {
        return [
            'index' => Index::class,
            'update' => Update::class,
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => ['index'],
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }
}
