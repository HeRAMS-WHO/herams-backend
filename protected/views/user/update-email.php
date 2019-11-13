<?php

/** @var \prime\models\forms\user\UpdateEmailForm $model */

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\bootstrap\Html;

echo Form::widget([
    'model' => $model,
    'form' => ActiveForm::begin([
        'formConfig' => [
            'showLabels' => true
        ],
        'fieldConfig' => [
//            'autoPlaceholder' => true,
        ]

    ]),
    'attributes' => [
        'newEmail' => [
            'type' => Form::INPUT_TEXT,
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                Html::submitButton(
                    Yii::t('app', 'Send confirmation'),
                    ['class' => 'btn btn-primary btn-block', 'tabindex' => '4']
                )
            ]
        ])
    ]
]);

ActiveForm::end();