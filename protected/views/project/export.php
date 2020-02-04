<?php

use app\components\Form;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\CsvExport $model
 * @var \prime\models\ar\Project $subject
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];

$this->title = \Yii::t('app', 'Export data from project {project}', ['project' => $subject->title]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-6"><?php
    $form = ActiveForm::begin([
        'id' => 'export',
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'labelSpan' => 6,
            'defaultPlaceholder' => false
        ],
        'options' => [
            'style' => [
                'column-span' => 4
            ]
        ]
    ]);

    echo \app\components\Form::widget([
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
    echo \yii\bootstrap\ButtonGroup::widget([
        'options' => [
            'class' => [
                'pull-right'
            ],
        ],
        'buttons' => [
            Html::submitButton(\Yii::t('app', 'Export'), ['class' => 'btn btn-primary']),

        ]
    ]);
    $form->end();
?></div>
