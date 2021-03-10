<?php

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

Section::begin()
    ->withHeader(\Yii::t('app', 'Create account'), ['style' => ['display' => 'block']]);

echo Html::tag('p', \Yii::t('app', 'Enter the email address you want to use for your account.'));

$form = ActiveForm::begin([

]);

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'email' => [
            'type' => Form::INPUT_TEXT,
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
