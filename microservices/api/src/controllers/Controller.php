<?php

declare(strict_types=1);

namespace herams\api\controllers;

use yii\filters\AccessControl;

abstract class Controller extends \yii\rest\Controller
{
    public $enableCsrfValidation = false;

    public function behaviors(): array
    {
        return [
            ...parent::behaviors(),
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }
}
