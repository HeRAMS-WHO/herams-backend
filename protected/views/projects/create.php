<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

$this->title = Yii::t('app', 'Create project');

$this->params['subMenu'] = [
    'items' => [
        '<li>' . Html::submitButton(\Yii::t('app', 'Create workspace'), ['form' => 'create-project', 'class' => 'btn btn-primary']) . '</li>'
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
            'owner_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->ownerOptions()
            ],
            'data_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->dataSurveyOptions()
            ],
            'token' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\DepDrop::class,
                'options' => [
                    'pluginOptions' => [
                        'url' => ['/projects/dependent-tokens'],
                        'depends' => ['createupdate-data_survey_eid'],
                        'initialize' => false,
                        'placeholder' => \Yii::t('app', 'Create new token')
                    ],
                ],
                'enableClientValidation' => false
            ],

//            'default_generator' => [
//                'type' => Form::INPUT_WIDGET,
//                'widgetClass' => \kartik\widgets\DepDrop::class,
//                'options' => [
//                    'pluginOptions' => [
//                        'url' => ['/tools/dependent-generators'],
//                        'depends' => ['createupdate-tool_id'],
//                        'initialize' => true,
//                        'placeholder' => null
//                    ],
//                ],
//                'enableClientValidation' => false
//            ],

        ]
    ]);

    $form->end();
    ?>
</div>