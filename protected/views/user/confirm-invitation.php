<?php
declare(strict_types=1);

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\ConfirmInvitationForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var ConfirmInvitationForm $model
 */

$this->title = \Yii::t('app', 'Create account');

Section::begin()
    ->withHeader($this->title, ['style' => ['display' => 'block']]);

$form = ActiveForm::begin([

]);

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'name' => [
            'type' => Form::INPUT_TEXT,
            'label' => \Yii::t('app', 'Display name'),
            'hint' => \Yii::t('app', 'This is used for display purposes only')
        ],
        'email' => [
            'label' => \Yii::t('app', 'Creating a new account for email:'),
            'type' => Form::INPUT_STATIC,
        ],
        'password' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'new-password'
            ]
        ],
        'confirmPassword' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'new-password'
            ]
        ],
        'subscribeToNewsletter' => [
            'type' => Form::INPUT_CHECKBOX,
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                ['label' => Yii::t('app', 'Create account'), 'style' => 'primary'],
            ],
        ]),
    ],
]);

ActiveForm::end();

Section::end();
