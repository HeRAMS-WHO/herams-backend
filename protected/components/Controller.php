<?php

namespace prime\components;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class Controller extends \Befound\Components\Controller
{
    public $layout = 'oneRow';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'ruleConfig' => [
                        'class' => AccessRule::class
                    ],
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