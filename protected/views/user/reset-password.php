<?php
declare(strict_types=1);

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\ResetPasswordForm;
use prime\widgets\ButtonGroup;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var ResetPasswordForm $model
 */

$this->title = Yii::t('app', 'Reset your password');

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin();

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'password' => [
            'type' => Form::INPUT_PASSWORD
        ],
        'password_repeat' => [
            'type' => Form::INPUT_PASSWORD
        ],
        FormButtonsWidget::embed([
            'buttons' =>  [
                ['label' => \Yii::t('app', 'Back to sign in'), 'type' => ButtonGroup::TYPE_LINK, 'link' => ['/session/create']],
                ['label' => \Yii::t('app', 'Reset password'), 'style' => 'primary'],
            ]
        ])
    ]
]);

ActiveForm::end();

Section::end();
