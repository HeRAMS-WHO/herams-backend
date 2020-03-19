<?php

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var User $model */

    echo Form::widget([
        'model' => $model,
        'form' => $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'type' => ActiveForm::TYPE_VERTICAL,
            'formConfig' => [
                'labelSpan' => 3,

            ],
            'fieldConfig' => [
            ]
        ]),
        'columns' => 1,
        'attributes' => [
            [
                'type' => Form::INPUT_RAW,
                'value' => $form->errorSummary($model)
            ],
            'name' => [
                'type' => Form::INPUT_TEXT
            ],
            FormButtonsWidget::embed([
                'buttons' => [
                    Html::submitButton(Yii::t('app', 'Update account information'), ['class' => 'btn btn-primary btn-block'])
                ]
            ])
        ]
    ]);
        ActiveForm::end();
