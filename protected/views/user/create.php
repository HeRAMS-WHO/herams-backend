<?php

declare(strict_types=1);

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\CreateForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var CreateForm $model
 */

$this->title = Yii::t('app', 'Create account');

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin();

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'email' => [
            'label' => \Yii::t('app', 'Creating a new account for email:'),
            'type' => Form::INPUT_STATIC,
        ],
        'name' => [
            'type' => Form::INPUT_TEXT
        ],
        'password' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'new-password'
            ]
        ],
        'confirm_password' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'new-password'
            ]
        ],
        'subscribeToNewsletter' => [
            'type' => Form::INPUT_CHECKBOX,
        ],
        FormButtonsWidget::embed([
            'options' => [
                'class' => [
                    'pull-right'
                ],
            ],
            'buttons' => [
                Html::submitButton(Yii::t('app', 'Create account'), ['class' => ['btn', 'btn-primary', 'btn-block']])
            ]
        ])

    ]
]);

ActiveForm::end();

Section::end();
