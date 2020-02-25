<?php

/** @var \prime\models\ar\Project $model */

use app\components\Form;
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

$this->title = $model->title;
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
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'latitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'longitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'status' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->statusOptions()
            ],
            'visibility' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->visibilityOptions()
            ],
            'typemapAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],
            'overridesAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => ButtonGroup::widget([
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
