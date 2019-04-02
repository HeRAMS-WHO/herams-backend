<?php

/**
 * @var \dektrium\user\models\RecoveryForm $model
 */

use app\components\Form;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Html;

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