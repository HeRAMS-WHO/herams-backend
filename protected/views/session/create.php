<?php

use app\components\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
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
                    'autocomplete' => 'username',
                    'placeholder' => 'email'
                ]
            ],
            'password' => [
                'type' => Form::INPUT_PASSWORD,
                'options' => [
                    'autocomplete' => 'current-password',
                    'placeholder' => 'password'
                ]
            ],
            FormButtonsWidget::embed([
                'orientation' => FormButtonsWidget::ORIENTATION_BLOCK,
                'options' => [
                    'class' => [
                        'pull-right'
                    ],
                ],
                'buttons' => [
                    Html::a(\Yii::t('app', 'Forgot your password ?'), ['/user/request-reset'], ['class' => 'request-reset']),
                    Html::submitButton(\Yii::t('app', 'Log in'), ['class' => 'btn btn-primary', 'form' => 'login-form'])
                ]
            ])
        ]
    ]);

    ActiveForm::end();
    
    echo Html::endTag('div');
    echo Html::tag('hr');
        echo Html::beginTag('div', ['class' => 'signup']);
        echo Html::tag('span', \Yii::t('app', "Register"), ['class' => 'title']);
        echo Form::widget([
            'model' => $requestAccountForm,
            'form' => $form = ActiveForm::begin([
                'action' => ['/user/request-account'],
                'id' => 'request-form',
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'validateOnBlur' => true,
                'validateOnType' => false,
                'validateOnChange' => false,
                'type' => ActiveForm::TYPE_VERTICAL,
            ]),
            'columns' => 1,
            'attributes' => [
                'email' => [
                    'type' => Form::INPUT_TEXT,
                    'options' => [
                        'placeholder' => 'email'
                    ]
                ],
                FormButtonsWidget::embed([
                    'orientation' => FormButtonsWidget::ORIENTATION_BLOCK,
                    'options' => [
                        'class' => [
                            'pull-right'
                        ],
                    ],
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Register'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ]
        ]);
        ActiveForm::end();
        echo Html::endTag('div');
        echo Html::endTag('div');
