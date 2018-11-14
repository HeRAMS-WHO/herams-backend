<?php
/** @var \prime\models\ar\Tool $model */

use app\components\ActiveForm;
use app\components\Form;
use app\components\Html;

$this->title = Yii::t('app', 'Create project');

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Back home'),
    'url' => '/'
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Manage projects'),
    'url' => ['tools/list']
];

?>
<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'create-tool',
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
            'base_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->dataSurveyOptions(),
                'options' => [
                    'prompt' => ''
                ]
            ],
        ],
        'options' => [
        ]
    ]);
    echo \yii\bootstrap\ButtonGroup::widget([
        'buttons' => [
            Html::submitButton(\Yii::t('app', 'Create project'), ['form' => 'create-tool', 'class' => 'btn btn-primary'])
        ]
    ]);

    $form->end();
    ?>
</div>