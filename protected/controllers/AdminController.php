<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\admin\Dashboard;
use prime\controllers\admin\Share;
use yii\helpers\ArrayHelper;

class AdminController extends Controller
{
    public $defaultAction = 'dashboard';

    public $layout = self::LAYOUT_ADMIN;

    public function actions(): array
    {
        return [
            'dashboard' => Dashboard::class,
            'share' => Share::class,
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
                            'allow' => ['dashboard'],
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }
}
