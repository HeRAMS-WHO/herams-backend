<?php

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\ChangePasswordForm;
use prime\widgets\FormButtonsWidget;
use yii\web\View;

/**
 * @var View $this
 * @var ChangePasswordForm $model
 */

$form = ActiveForm::begin([
    'action' => ['/user/update-password'],
]);

echo Form::widget([
    'model' => $model,
    'form' => $form,
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
            'buttons' => [
                ['label' => Yii::t('app', 'Update password'), 'style' => 'primary'],
            ]
        ])
    ]
]);

ActiveForm::end();
