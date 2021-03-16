<?php
declare(strict_types=1);

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\AcceptInvitationForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var AcceptInvitationForm $model
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
        FormButtonsWidget::embed([
            'buttons' => [
                ['label' => Yii::t('app', 'Create account'), 'style' => 'primary'],
            ],
        ]),
    ],
]);

ActiveForm::end();

Section::end();
