<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

$this->title = Yii::t('app', 'Update project');

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'save'), ['form' => 'update-project', 'class' => 'btn btn-primary'])
        ],
    ]
];
?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'update-project',
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
            'default_generator' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->generatorOptions(),
                'options' => [
                    'prompt' => \Yii::t('app', "None")
                ]
            ],
            'countriesIds' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::class,
                'options' => [
                    'data' => $model->countriesOptions(),
                    'options' => [
                        'multiple' => true
                    ]
                ]
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