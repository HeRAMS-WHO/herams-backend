<?php
/** @var \prime\models\forms\user\ChangePasswordForm $model */

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\bootstrap\Html;

echo Form::widget([
    'model' => $model,
    'form' => ActiveForm::begin([
        'action' => ['/user/update-password'],
        'formConfig' => [
            'showLabels' => true
        ],
        'fieldConfig' => [
//            'autoPlaceholder' => true,
        ]

    ]),
    'attributes' => [
        'currentPassword' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'current-password'
            ]
        ],
        'newPassword' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'new-password'
            ]
        ],
        'newPasswordRepeat' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'new-password'
            ]
        ],
        FormButtonsWidget::embed([
            'options' => [
                'class' => [
                    'pull-right'
                ],
            ],
            'buttons' => [
                Html::submitButton(
                    Yii::t('app', 'Update password'),
                    ['class' => 'btn btn-primary btn-block']
                )
            ]
        ])
    ]
]);

ActiveForm::end();
