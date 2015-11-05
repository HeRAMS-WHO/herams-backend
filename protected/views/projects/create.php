<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

$this->title = Yii::t('app', 'Create project');
?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'create-project',
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false
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
            'owner_id' => [
                'label' => \Yii::t('app', 'Owner'),
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \yii\helpers\ArrayHelper::map(\prime\models\User::find()->all(), 'id', 'name')
            ],
            'tool_id' => [
                'label' => \Yii::t('app', 'Tool'),
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \yii\helpers\ArrayHelper::map(\prime\models\Tool::find()->all(), 'id', 'title'),
            ],
            'data_survey_eid' => [
                'type' => Form::INPUT_HTML5,
                'html5type' => 'number'
            ],
            'default_generator' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\DepDrop::class,
                'options' => [
                    'pluginOptions' => [
                        'url' => ['/tools/dep-drop-generators'],
                        'depends' => ['project-tool_id']
                    ],
                ],
                'enableClientValidation' => false
            ],
            'countriesIds' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::class,
                'options' => [
                    'data' => $model->countriesOptions,
                    'options' => [
                        'multiple' => true
                    ]
                ]
            ],
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' =>
                    Html::submitButton(\Yii::t('app', 'Submit'), ['class' => 'btn btn-primary col-xs-12'])
            ]
        ]
    ]);

    $form->end();
    ?>
</div>