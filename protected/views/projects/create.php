<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \prime\models\ar\Project $model
 */

$this->title = Yii::t('app', 'Create project');

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'save'), ['form' => 'create-project', 'class' => 'btn btn-primary'])
        ],
    ]
];
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
                'items' => $model->ownerOptions()
            ],
            'tool_id' => [
                'label' => \Yii::t('app', 'Tool'),
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->toolOptions()
            ],
            'data_survey_eid' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\DepDrop::class,
                'options' => [
                    'pluginOptions' => [
                        'url' => ['/tools/dependent-surveys'],
                        'depends' => ['createupdate-tool_id'],
                        'initialize' => true,
                    ],
                ],
                'enableClientValidation' => false
            ],
            'default_generator' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\DepDrop::class,
                'options' => [
                    'pluginOptions' => [
                        'url' => ['/tools/dependent-generators'],
                        'depends' => ['createupdate-tool_id'],
                        'initialize' => true,
                    ],
                ],
                'enableClientValidation' => false
            ],
            'countriesIds' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::class,
                'options' => [
                    'data' => $model->countriesOptions(),
                    'options' => [
                        'multiple' => true
                    ],
                ]
            ]
        ]
    ]);

    $form->end();
    ?>
</div>