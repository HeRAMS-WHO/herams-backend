<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

$this->title = Yii::t('app', 'Create tool');
?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'settings-profile-form',
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false
        ],
        'options' => [
            'enctype'=>'multipart/form-data'
        ]
    ]);

    echo \app\components\Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'description' => [
                'type' => Form::INPUT_TEXTAREA
            ],
            'tempImage' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\file\FileInput::class
            ],
            'intake_survey_eid' => [
                'type' => Form::INPUT_HTML5,
                'html5type' => 'number'
            ],
            'base_survey_eid' => [
                'type' => Form::INPUT_HTML5,
                'html5type' => 'number'
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