<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\accessRequest\Index;
use prime\controllers\accessRequest\Respond;
use yii\helpers\ArrayHelper;

class AccessRequestController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function actions(): array
    {
        return [
            'index' => Index::class,
            'respond' => Respond::class,
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => [
                            'index',
                            'respond',
                        ],
                    ]
                ]
            ]
        ]);
    }
}
