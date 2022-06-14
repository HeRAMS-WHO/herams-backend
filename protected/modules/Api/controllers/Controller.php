<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use yii\filters\AccessControl;

abstract class Controller extends \yii\rest\Controller
{
    public $enableCsrfValidation = false;

    public function behaviors(): array
    {
        $result =  [
            ...parent::behaviors(),
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false
                    ]
                ],
            ],
        ];
        return $result;
    }
}
