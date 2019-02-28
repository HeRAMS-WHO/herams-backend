<?php
/** @var \prime\models\ar\Project $model */

use kartik\widgets\ActiveForm;
use app\components\Form;
use yii\bootstrap\Html;

$this->title = Yii::t('app', 'Create project');

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Manage projects'),
    'url' => ['project/index']
];

?>
<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
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
        "attributes" => [
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'base_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->dataSurveyOptions(),
                'options' => [
                    'prompt' => ''
                ]
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Create project'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ]
        ],
        'options' => [
        ]
    ]);
    $form->end();
    ?>
</div>