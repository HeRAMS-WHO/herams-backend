<?php

namespace prime\components;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                    ]
                ]
            ]
        );
    }
}