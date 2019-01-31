<?php

/** @var \prime\models\ar\Tool $model */

use kartik\widgets\ActiveForm;
use app\components\Form;
use yii\bootstrap\Html;

$this->title = Yii::t('app', 'Update project');

?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'update-tool',
        'method' => 'PUT',
        "type" => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo \app\components\Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'base_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->dataSurveyOptions(),
            ],
            'latitude_code' => [
                'type' => Form::INPUT_TEXT
            ],
            'longitude_code' => [
                'type' => Form::INPUT_TEXT
            ],
            'name_code' => [
                'type' => Form::INPUT_TEXT
            ],
            'type_code' => [
                'type' => Form::INPUT_TEXT
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Update project'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ]
        ]
    ]);
    $form->end();

    ?>
</div>
<div class="col-xs-12">
    <?php
        echo \kartik\grid\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getAllPages()]),
            'columns' => [
                'id',
                'title',
                'parent_id'
            ]
        ]);

    ?>
</div>