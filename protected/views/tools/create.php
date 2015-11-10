<?php
/** @var \prime\models\Tool $model */
use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

$this->title = Yii::t('app', 'Create tool');

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'Save'), ['form' => 'create-tool', 'class' => 'btn btn-primary'])
        ],
    ]
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
            'description' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \dosamigos\ckeditor\CKEditor::class,
                'options' => [
                    'preset' => 'basic'
                ]
            ],
            'progress_type' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \yii\helpers\ArrayHelper::map(
                    array_flip($model->progressOptions()),
                    function ($key) {
                        return $key;
                    },
                    function ($key) use ($model) {
                        $class = $model->progressOptions()[$key];
                        return (new $class)->title;
                    }
                )
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
                'items' => $model->intakeSurveyOptions()
            ],
            'base_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->dataSurveyOptions()
            ],
            'generators' => [
                'type' => Form::INPUT_CHECKBOX_LIST,
                'items' => \yii\helpers\ArrayHelper::map(
                    array_flip($model->generatorOptions()),
                    function ($key) {
                        return $key;
                    },
                    function ($key) use ($model) {
                        $class = $model->generatorOptions()[$key];
                        return (new $class)->title;
                    }
                )
            ],
        ],
        'options' => [

        ]
    ]);

    $form->end();
    ?>
</div>