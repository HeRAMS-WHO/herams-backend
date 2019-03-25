<?php

namespace prime\components;

use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;

class Controller extends \yii\web\Controller
{
    use ActionInjectionTrait;
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

    public function render($view, $params = [])
    {
        \Yii::beginProfile(__FUNCTION__);
        $result = parent::render($view, $params);
        \Yii::endProfile(__FUNCTION__);
        return $result;
    }
}