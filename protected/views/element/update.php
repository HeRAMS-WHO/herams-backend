<?php

/** @var \prime\models\ar\Element $model */

use app\components\Form;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/update', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => $page->title,
    'url' => ['page/update', 'id' => $page->id]
];

$this->title = \Yii::t('app', 'Update element');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'method' => 'PUT',
        "type" => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo Form::widget([
        'form' => $form,

        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'type' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->typeOptions()
            ],
            'sort' => [
                'type' => Form::INPUT_HTML5,
                'html5type' => 'number'
            ],
            'transpose' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'code' => [
                 'type' => Form::INPUT_WIDGET,
                'widgetClass' => Select2::class,
                'options' => [
                    'data' => $codeOptions,
                ],
            ],
            'configAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],


        ]
    ]);
    $attributes = [];
    for($i = 0; $i < 20; $i++) {
        $attributes["colors[$i][code]"] = [
            'type' => Form::INPUT_TEXT,
            'label' => 'Answer code',
            'options' => [
                'placeholder' => 'No answer given / not shown'
            ],
            'allowUnsafe' => true
        ];
        $attributes["colors[$i][color]"] = [
            'type' => Form::INPUT_HTML5,
            'html5type' =>'color',
            'allowUnsafe' => true,
            'label' => 'Color'
        ];

    }

    $form->formConfig['labelSpan'] = 4;
    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 2,
        'attributes' => $attributes

    ]);
    echo ButtonGroup::widget([
        'buttons' => [
            Html::submitButton(\Yii::t('app', 'Update element'), ['class' => 'btn btn-primary'])
        ]
    ]);
    $form->end();

    ?>
</div>