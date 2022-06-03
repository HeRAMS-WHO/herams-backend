<?php

declare(strict_types=1);

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\UpdatePasswordForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\UserTabMenu;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var UpdatePasswordForm $model
 */

$this->beginBlock('tabs');
echo UserTabMenu::widget([
    'user' => $model->getUser(),
]);
$this->endBlock();

$this->title = Yii::t('app', 'Password');

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin([

]);

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'currentPassword' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'current-password',
            ],
        ],
        'newPassword' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'new-password',
            ],
        ],
        'newPasswordRepeat' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'new-password',
            ],
        ],
        FormButtonsWidget::embed(
            [
                'buttons' => [
                    [
                        'label' => Yii::t('app', 'Update password'),
                        'style' => 'primary',
                    ],
                ],
            ]
        ),

    ],
]);

ActiveForm::end();

Section::end();
