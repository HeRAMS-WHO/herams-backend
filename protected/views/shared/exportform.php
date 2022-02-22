<?php

declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\widgets\FormButtonsWidget;
use yii\helpers\Html;

/**
 * @var \prime\models\forms\Export $model
 * @var
 */
$form = ActiveForm::begin([
    'id' => 'export',
    'method' => 'POST',
    "type" => ActiveForm::TYPE_HORIZONTAL
]);
echo Form::widget([
    'form' => $form,
    'model' => $model,
    'columns' => 1,
    "attributes" => [
        'includeTextHeader' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\switchinput\SwitchInput::class
        ],
        'includeCodeHeader' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\switchinput\SwitchInput::class
        ],
        'answersAsText' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\switchinput\SwitchInput::class
        ],
        'language' => [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => $model->getLanguages()

        ],

    ]
]);

echo Form::widget([
    'form' => $form,
    'model' => $model->getFilterModel(),
    'attributes' => [
        'date' => [
            'label' => \Yii::t('app', 'Report date'),
            'hint' => \Yii::t('app', 'Enter a date to limit the export to the last record before or equal to the selected date for each health facility. To export all historic records of a health facility, leave the field blank.'),
            'type' => Form::INPUT_HTML5,
            'html5type' => 'date',
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                Html::submitButton(\Yii::t('app', 'Export CSV'), [
                    'class' => 'btn btn-primary',
                    'name' => 'format',
                    'value' => 'csv',
                ]),
                Html::submitButton(\Yii::t('app', 'Export XLSX'), [
                    'class' => 'btn btn-default',
                    'name' => 'format',
                    'value' => 'xlsx'
                ]),
            ]
        ])
    ]
]);
$form->end();
