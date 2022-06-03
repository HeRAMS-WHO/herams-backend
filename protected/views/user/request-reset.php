<?php

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\RequestResetForm;
use prime\widgets\ButtonGroup;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var RequestResetForm $model
 */

$this->title = Yii::t('app', 'Reset password');

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin();

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'email' => [
            'type' => Form::INPUT_TEXT,
        ],
        FormButtonsWidget::embed(
            [
                'buttons' => [
                    [
                        'label' => \Yii::t('app', 'Back to sign in'),
                        'type' => ButtonGroup::TYPE_LINK,
                        'link' => ['/session/create'],
                    ],
                    [
                        'label' => \Yii::t('app', 'Request password reset'),
                        'style' => 'primary',
                    ],
                ],
            ]
        ),
    ],
]);

ActiveForm::end();

Section::end();
