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
                ],
                'hint' => \Yii::t('app', 'When latitude and longitude are not set, center of countries is used as project location')
            ],
            'latitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'longitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'map' => [
                'type' => Form::INPUT_RAW,
                'value' => function(\prime\models\forms\projects\CreateUpdate $model, $index, Form $form) {
                    $this->registerAssetBundle(\prime\assets\LocationPickerAsset::class);
                    $this->registerJs("$('#{$form->getId()} .location-picker').locationpicker({
                        zoom: 2,
                        radius: false,
                        inputBinding: {
                            latitudeInput: $('#{$form->getId()} [name=\'" . Html::getInputName($model, 'latitude') . "\']'),
                            longitudeInput: $('#{$form->getId()} [name=\'" . Html::getInputName($model, 'longitude') . "\']')
                        },
                        oninitialized: function(component) {
                            var e = $.Event('change');
                            e.originalEvent = true;
                            this.inputBinding.latitudeInput.val(this.inputBinding.latitudeInput.attr('value')).trigger(e);
                            this.inputBinding.longitudeInput.val(this.inputBinding.longitudeInput.attr('value')).trigger(e);
                        }
                    });
                    ");
                    return Html::beginTag('div', ['class' => 'form-group']) .
                    Html::beginTag('div', ['class' => 'col-md-offset-2 col-md-10']) .
                    Html::tag('div', '', ['style' => ['height' => '400px'], 'class' => ['form-control', 'location-picker']]) .
                    Html::endTag('div') .
                    Html::tag('div', '', ['class' => 'col-md-offset-2 col-md-10']) .
                    Html::beginTag('div', ['class' => 'col-md-offset-2 col-md-10']) .
                    Html::tag('div', '', ['class' => 'help-block']) .
                    Html::endTag('div') .
                    Html::endTag('div');
                }
            ]
        ]
    ]);

    $form->end();
    ?>
</div>