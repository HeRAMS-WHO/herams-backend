<?php

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\Export $model
 * @var \prime\models\ar\Workspace $subject
 */

$this->params['breadcrumbs'][] = [
    'label' => $subject->project->title,
    'url' => ['project/workspaces', 'id' => $subject->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $subject->title,
    'url' => ['workspace/limesurvey', 'id' => $subject->id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Export workspace'),
    'url' => ['workspace/export', 'id' => $subject->id]
];
$this->title = \Yii::t('app', 'Export data from workspace {workspace}', ['workspace' => $subject->title]);

echo Html::beginTag('div', ['class' => 'content no-tab']);
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
        'class' => 'col-xs-12 col-md-6 full-width',
        'style' => [
            'column-span' => 4
        ]
    ]
]);
echo \yii\bootstrap\Collapse::widget([
    'autoCloseItems' => false,
    'items' => [
        [
            'label' => 'Settings',
            // open its content by default
            'contentOptions' => ['class' => 'in'],
            'content' => Form::widget([
                'form' => $form,
                'model' => $model,
                'columns' => 1,
                "attributes" => [
                    'includeTextHeader' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => \kartik\switchinput\SwitchInput::class,
                    ],
                    'includeCodeHeader' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => \kartik\switchinput\SwitchInput::class
                    ],
                    'answersAsText' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => \kartik\switchinput\SwitchInput::class,
                    ],
                    'language' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => $model->getLanguages()
                    ],
                ]
            ])
        ],
        [
            'label' => 'Advanced settings',
            'content' => Form::widget([
                'form' => $form,
                'model' => $model->getFilterModel(),
                'attributes' => [
                    'date' => [
                        'label' => \Yii::t('app', 'Report date'),
                        'hint' => \Yii::t('app', 'Enter a date to limit the export to the last record before or equal to the selected date for each health facility. To export all historic records of a health facility, leave the field blank.'),
                        'type' => Form::INPUT_HTML5,
                        'html5type' => 'date',
                    ],
                ]
            ])
        ]
    ]
]);
echo Form::widget([
    'form' => $form,
    'model' => $model->getFilterModel(),
    'attributes' => [
        FormButtonsWidget::embed([
            'options' => [
                'class' => [
                    'pull-right'
                ],
            ],
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
echo Html::endTag('div');
