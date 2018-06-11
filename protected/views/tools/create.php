<?php
/** @var \prime\models\ar\Tool $model */
use \app\components\Form;
use \app\components\ActiveForm;
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
            'acronym' => [
                'type' => Form::INPUT_TEXT,
            ],
            'description' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \dosamigos\ckeditor\CKEditor::class,
                'options' => [
                    'preset' => 'basic'
                ]
            ],
            'progress_type' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \prime\factories\GeneratorFactory::options(),
                'options' => [
                    'prompt' => ''
                ]
            ],
            'hidden' => [
                'type' => Form::INPUT_CHECKBOX,
                'hint' => \Yii::t('app', 'Check if the tool is not requestable for users')
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
                'items' => $model->intakeSurveyOptions(),
                'options' => [
                    'prompt' => ''
                ]
            ],
            'base_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->dataSurveyOptions(),
                'options' => [
                    'prompt' => ''
                ]
            ],
            'generatorsArray' => [
                'type' => Form::INPUT_CHECKBOX_LIST,
                'items' => \prime\factories\GeneratorFactory::options()
            ],
        ],
        'options' => [

        ]
    ]);

    $form->end();
    ?>
</div>