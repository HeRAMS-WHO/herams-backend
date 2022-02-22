<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\mail\Webhook;
use yii\helpers\ArrayHelper;

class MailController extends Controller
{
    public function actions(): array
    {
        return [
            'webhook' => Webhook::class,
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'webhook'
                            ],
                        ],
                    ]
                ],
            ]);
    }
}
