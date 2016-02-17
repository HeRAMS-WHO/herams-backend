<?php

namespace prime\components;

use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class Controller extends \Befound\Components\Controller
{
    use ActionInjectionTrait;
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