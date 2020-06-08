<?php

use app\components\Form;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = \Yii::$app->name;
    
    $this->params['breadcrumbs'] = [];
    /** @var \yii\web\View $this */

    echo Html::beginTag('div', ['class' => 'signin']);
    
    echo Html::tag('span', \Yii::t('app', "Log in"), ['class' => 'title']);

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
                    'autocomplete' => 'username'
                ]
            ],
            'password' => [
                'type' => Form::INPUT_PASSWORD,
                'options' => [
                    'autocomplete' => 'current-password'
                ]
            ]
        ]
    ]);

    echo Html::a(\Yii::t('app', 'Reset password'), ['/user/request-reset'], ['class' => 'request-reset']);
    echo Html::submitButton(\Yii::t('app', 'Log in'), ['class' => 'btn btn-primary', 'form' => 'login-form']);
    ActiveForm::end();
    
    echo Html::endTag('div');
    echo Html::tag('hr');
    echo Html::beginTag('div', ['class' => 'signup']);
    echo Html::tag('span', \Yii::t('app', "Register"), ['class' => 'title']);
    
    echo Html::beginTag('form');
    echo Html::tag('p', \Yii::t('app', 'Enter your email to receive a secure link to the 
sign up form'), ['class' => 'hint']);

    echo Html::a(\Yii::t('app', "Request account"), ['/user/request-account'], ['class' => 'btn btn-primary']);
    echo Html::endTag('div');
    echo Html::endTag('div');
