<?php

/**
 * @var \dektrium\user\models\RecoveryForm $model
 */

use app\components\ActiveForm;
use app\components\Form;
use app\components\Html;

$form = ActiveForm::begin([
    'id' => 'reset-form',
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
        'password' => [
            'type' => Form::INPUT_PASSWORD,
        ],
        'confirmPassword' => [
            'type' => Form::INPUT_PASSWORD
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