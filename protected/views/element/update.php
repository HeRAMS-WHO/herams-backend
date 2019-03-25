<?php

/** @var \prime\models\ar\Element $model */

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use app\components\Form;
use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;

$this->title = Yii::t('app', 'Update element');
$this->params['breadcrumbs'] = [
    [
        'label' => \Yii::t('app', "Update page: {page}", [
                'page' => $model->page->title,
        ]),
        'url' => ['page/update', 'id' => $model->page->id]
    ],
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title
//    'url' => ['project/view', 'id' => $project->id]
];
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
            'sort' => [
                'type' => Form::INPUT_HTML5,
                'html5type' => 'number'
            ],
            'transpose' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'configAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Update project'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ],

        ]
    ]);
    $form->end();

    ?>
</div>