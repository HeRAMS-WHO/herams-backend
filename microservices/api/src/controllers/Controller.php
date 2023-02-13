<?php

declare(strict_types=1);

namespace herams\api\controllers;

use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Response;

abstract class Controller extends \yii\rest\Controller
{
    public $enableCsrfValidation = false;

    public function behaviors(): array
    {
        return [
            ...parent::behaviors(),
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
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
