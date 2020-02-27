<?php

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\FormButtonsWidget;
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
            'class' => 'col-xs-6',
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
    echo Form::widget([
        'form' => $form,
        'model' => $model->getFilterModel(),
        'attributes' => [
            'date' => [
                'label' => \Yii::t('app', 'Report date'),
                'type' => Form::INPUT_HTML5,
                'html5type' => 'date',
                'hint' => Html::tag('p', \Yii::t('app', 'This will only export the last record before this date for each health facility'))
            ],
            FormButtonsWidget::embed([
                'options' => [
                    'class' => [
                        'pull-right'
                    ],
                ],
                'buttons' => [
                    Html::submitButton(\Yii::t('app', 'Export'), ['class' => 'btn btn-primary']),
                ]
            ])
        ]
    ]);
    $form->end();
