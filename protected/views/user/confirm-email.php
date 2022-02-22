<?php

declare(strict_types=1);

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\UpdateEmailForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\web\View;

/**
 * @var View $this
 * @var UpdateEmailForm $model
 */

Section::begin();

$form = ActiveForm::begin();

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'oldMail' => [
            'type' => Form::INPUT_STATIC,
        ],
        'newMail' => [
            'type' => Form::INPUT_STATIC,
        ],
        FormButtonsWidget::embed([
            'options' => [
                'class' => [
                    'pull-right'
                ],
            ],
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

Section::end();
