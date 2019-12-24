<?php

use app\components\Form;
use kartik\form\ActiveForm;
use yii\helpers\Html;


    $this->title = \Yii::$app->name;
    echo Html::tag('header', \Yii::t('app', 'Log in to HeRAMS'));
    $this->params['breadcrumbs'] = [];
    /** @var \yii\web\View $this */

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
            'login' =>[
                'type' => Form::INPUT_TEXT,
                'options' => [
                    'autocomplete' => 'username',
                    'placeholder' => 'Email'
                ]
            ],
            'password' => [
                'type' => Form::INPUT_PASSWORD,
                'options' => [
                    'autocomplete' => 'current-password',
                    'placeholder' => 'Password'
                ]
            ]
        ]
    ]);

    ActiveForm::end();
    echo Html::beginTag('div', ['class' => 'actions']);
    echo Html::a(\Yii::t('app', "Sign up"), ['/user/request-account']);
    echo Html::a(\Yii::t('app', "Reset password"), ['/user/request-reset']);
    echo Html::submitButton('Log in', ['class' => 'btn btn-primary', 'form' => 'login-form']);
    echo Html::endTag('div');
