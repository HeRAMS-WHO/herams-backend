<?php

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\UpdateEmailForm;
use prime\widgets\FormButtonsWidget;
use yii\web\View;

/**
 * @var View $this
 * @var UpdateEmailForm $model
 */

$form = ActiveForm::begin([
    'action' => ['/user/update-email'],
]);

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'newEmail' => [
            'type' => Form::INPUT_TEXT,
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                ['label' => Yii::t('app', 'Send confirmation'), 'style' => 'primary'],
            ],
        ]),
    ],
]);

ActiveForm::end();
