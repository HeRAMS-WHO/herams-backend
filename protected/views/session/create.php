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

    ActiveForm::end();
    echo Html::beginTag('div', ['class' => 'actions']);
    echo Html::submitButton(\Yii::t('app', 'Log in'), ['class' => 'btn btn-primary', 'form' => 'login-form']);
    echo Html::a(\Yii::t('app', 'Reset password'), ['/user/request-reset']);
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'signup']);
    echo Html::tag('span', \Yii::t('app', "Don't have an account?"), ['class' => 'title']);
    echo Html::a(\Yii::t('app', "Request account"), ['/user/request-account'], ['class' => 'btn btn-primary']);
    
    echo Html::endTag('div');
