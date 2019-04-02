<?php

/**
 * @var \dektrium\user\models\RecoveryForm $model
 */

use app\components\Form;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
    'id' => 'recovery-form',
    'method' => 'POST',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => false,
        'defaultPlaceholder' => true
    ]
]);

echo Form::widget([
    'form' => $form,
    'model' => $model,
    'columns' => 1,
    "attributes" => [
        'email' => [
            'type' => Form::INPUT_HTML5,
            'html5type' => 'email'
        ],
        'captcha' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \yii\captcha\Captcha::class,
            'options' => [
                'captchaAction' => ['/site/captcha'],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => \Yii::t('app', 'Copy the code above')
                ]
            ]
        ],
        'actions' => [
            'type' => Form::INPUT_RAW,
            'value' =>
                Html::submitButton(\Yii::t('app', 'Submit'), ['class' => 'btn btn-primary col-xs-12'])
        ]
    ],
    'options' => [

    ]
]);

$form->end();