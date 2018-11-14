<?php

use app\components\ActiveForm;
use app\components\Form;
use app\components\Html;

$this->title = Yii::t('user', 'Profile settings');

?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('@dektrium/user/views/settings/_menu') ?>
    </div>
    <div class="col-md-9">
        <?php
        $form = ActiveForm::begin([
            'id' => 'settings-profile-form',
            'method' => 'POST',
            "type" => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'showLabels' => true,
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
                'image' => [
                    'type' => Form::INPUT_RAW,
                    'value' => '<div class="form-group"><div class="col-md-10 col-md-offset-2">' .
                        \Yii::t('app', 'If you would like to add a profile image, please register your email address at {url}', [
                            'url' => Html::a('Gravatar', '//en.gravatar.com/connect/?source=_signup', ['target' => '_blank'])
                        ]) .
                        '</div></div><div class="help-block"></div>'
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
                'accessToken' => [
                    'type' => Form::INPUT_TEXT,
                    'hint' => \Yii::t('app', 'Sharing this will grant recipients full access to your account. Be careful!'),
                    'options' => [
                        'disabled' => true
                    ]
                ],

                'actions' => [
                    'type' => Form::INPUT_RAW,
                    'value' =>
                        Html::submitButton(\Yii::t('app', 'Submit'), ['class' => 'btn btn-primary col-xs-12'])
                ]
            ],
            'options' => [

            ]
        ]);

        $form->end();
        ?>
    </div>
</div>