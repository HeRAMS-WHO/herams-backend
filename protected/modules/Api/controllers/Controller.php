<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use yii\filters\AccessControl;
use yii\rest\Serializer;

abstract class Controller extends \yii\rest\Controller
{
    public $enableCsrfValidation = false;
    public $serializer = \prime\helpers\Serializer::class;

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
                        'allow' => false
                    ]
                ],
            ],
        ];
    }
}
