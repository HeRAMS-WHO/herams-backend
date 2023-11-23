<?php

declare(strict_types=1);

use app\components\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\helpers\Html;

/**
 * @var \prime\components\View $this
 * @var \prime\models\forms\LoginForm $model
 */
$this->title = \Yii::$app->name;

/** @var \yii\web\View $this */

echo Html::beginTag('div', [
    'class' => 'signin',
]);

echo Html::tag('span', \Yii::t('app', "Log in"), [
    'class' => 'title',
]);

$form = ActiveForm::begin([
    'id' => 'login-form',
    'enableAjaxValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]);
echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'login' => [
            'type' => Form::INPUT_TEXT,
            'options' => [
                'autocomplete' => 'username',
                'placeholder' => 'email',
            ],
        ],
        'password' => [
            'type' => Form::INPUT_PASSWORD,
            'options' => [
                'autocomplete' => 'current-password',
                'placeholder' => 'password',
            ],
        ],
        [
            'type' => 'raw',
            'value' => Html::a(
                \Yii::t('app', 'Forgot your password ?'),
                ['/user/request-reset'],
                [
                    'class' => 'request-reset',
                ]
            ),
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                [
                    'type' => 'submit',
                    'label' => \Yii::t('app', 'Log in'),
                    'style' => 'primary',
                ],
            ],
        ]),

    ],
]);

ActiveForm::end();

echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
