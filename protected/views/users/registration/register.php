<?php

/**
 * @var \prime\models\forms\user\Registration $model
 */

use app\components\ActiveForm;
use app\components\Form;
use app\components\Html;

$form = ActiveForm::begin([
    'id' => 'register-form',
    'method' => 'POST',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'labelSpan' => 0,
        'showLabels' => ActiveForm::SCREEN_READER,
        'defaultPlaceholder' => true
    ]
]);

echo Form::widget([
    'form' => $form,
    'model' => $model,
    'columns' => 1,
    "attributes" => [
        'first_name' => [
            'type' => Form::INPUT_TEXT,
        ],
        'last_name' => [
            'type' => Form::INPUT_TEXT
        ],
        'password' => [
            'type' => Form::INPUT_PASSWORD,
        ],
        'confirmPassword' => [
            'type' => Form::INPUT_PASSWORD
        ],
        'email' => [
            'type' => Form::INPUT_HTML5,
            'html5type' => 'email'
        ],
        'image' => [
            'type' => Form::INPUT_RAW,
            'value' => '<div class="form-group">' .
                \Yii::t('app', 'If you would like to add a profile image, please register your email address at {url}', [
                    'url' => Html::a('Gravatar', '//en.gravatar.com/connect/?source=_signup', ['target' => '_blank'])
                ]) .
                '</div><div class="help-block"></div>'
        ],
        'organization' => [
            'type' => Form::INPUT_TEXT,
        ],
        'country' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\select2\Select2::class,
            'options' => [
                'data' => $model->countryOptions(),
                'options' => [
                    'placeholder' => \Yii::t('app', 'Country')
                ]
            ]
        ],
        'office' => [
            'type' => Form::INPUT_TEXT,
        ],
        'position' => [
            'type' => Form::INPUT_TEXT
        ],
        'phone' => [
            'type' => Form::INPUT_TEXT
        ],
        'phone_alternative' => [
            'type' => Form::INPUT_TEXT
        ],
        'other_contact' => [
            'type' => Form::INPUT_TEXT
        ],
        'captcha' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \yii\captcha\Captcha::class,
            'options' => [
                'captchaAction' => ['/site/captcha'],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => \Yii::t('app', 'Copy the code above')
                ]
            ]
        ],
        'actions' => [
            'type' => Form::INPUT_RAW,
            'value' =>
                '<div class="form-group">' . Html::submitButton(\Yii::t('app', 'Submit'), ['class' => 'btn btn-primary btn-block']) . '</div>'
        ]
    ],
    'options' => [

    ]
]);

$form->end();