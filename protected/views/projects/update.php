<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\Project $model
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
                'items' => \yii\helpers\ArrayHelper::map(
                    array_flip($model->tool->getGenerators()),
                    function ($key) {
                        return $key;
                    },
                    function ($key) {
                        $class = \prime\models\Tool::generatorOptions()[$key];
                        return (new $class)->title;
                    }
                ),
                'options' => [
                    'prompt' => ''
                ]
            ]
        ]
    ]);

    $form->end();
    ?>
</div>