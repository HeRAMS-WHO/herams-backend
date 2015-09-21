<?php

/**
 * @var \app\models\User $user
 */

use app\components\Form;
use app\components\Html;
use app\components\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'login',
    'method' => 'POST',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => false
    ]
]);

echo Form::widget([
    'form' => $form,
    'model' => $user,
    'columns' => 1,
    "attributes" => [
        'email' => [
            'type' => Form::INPUT_TEXT
        ],
        'password' => [
            'type' => Form::INPUT_PASSWORD
        ],
        'actions' => [
            'type' => Form::INPUT_RAW,
            'value' =>
                Html::submitButton(\Yii::t('app', 'Login'), ['class' => 'btn btn-primary col-xs-12']) .
                ' ' .
                Html::a(
                    \Yii::t('app', 'Reset password'),
                    [
                        'users/request-password-reset'],
                    [
                        'class' => 'btn btn-default col-xs-12',
                        'style' => 'margin-top: 10px'
                    ]
                )
        ]
    ],
    'options' => [

    ]
]);

$form->end();