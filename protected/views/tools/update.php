<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

$this->title = Yii::t('app', 'Update tool');

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'Save'), ['form' => 'update-tool', 'class' => 'btn btn-primary'])
        ],
    ]
];

?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'update-tool',
        'method' => 'PUT',
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
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \dosamigos\ckeditor\CKEditor::class,
                'options' => [
                    'preset' => 'basic'
                ]
            ],
            'tempImage' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\file\FileInput::class
            ],
            'thumbTempImage' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\file\FileInput::class
            ],
            'intake_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->intakeSurveyOptions()
            ],
            'base_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->dataSurveyOptions()
            ],
            'generators' => [
                'type' => Form::INPUT_CHECKBOX_LIST,
                'items' => \yii\helpers\ArrayHelper::map(
                    array_flip(\prime\models\Tool::generatorOptions()),
                    function ($key) {
                        return $key;
                    },
                    function ($key) {
                        $class = \prime\models\Tool::generatorOptions()[$key];
                        return (new $class)->title;
                    }
                ),
                'value' => $model->generators->asArray()
            ],
        ]
    ]);

    $form->end();
    ?>
</div>