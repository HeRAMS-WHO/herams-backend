<?php
/** @var \prime\models\forms\user\UpdateEmailForm $model */

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\bootstrap\Html;

echo Form::widget([
    'model' => $model,
    'form' => ActiveForm::begin(),
    'attributes' => [
        'oldMail' => [
            'type' => Form::INPUT_STATIC,
        ],
        'newMail' => [
            'type' => Form::INPUT_STATIC,
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                Html::submitButton(
                    Yii::t('app', 'Apply changes'),
                    ['class' => 'btn btn-primary btn-block', 'tabindex' => '4']
                )
            ]
        ])
    ]
]);

ActiveForm::end();