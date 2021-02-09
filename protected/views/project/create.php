<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\components\View;
use prime\models\ar\Project;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;

/**
 * @var View $this
 * @var Project $model
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->title = Yii::t('app', 'Create project');

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin([
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => true,
        'defaultPlaceholder' => false,
        'labelSpan' => 3
    ]
]);
echo Form::widget([
    'form' => $form,
    'model' => $model,
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
        'visibility' => [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => $model->visibilityOptions()
        ],
        FormButtonsWidget::embed([
            'buttons' =>  [
                ['label' => \Yii::t('app', 'Create project'), 'style' => 'primary'],
            ]
        ])
    ],
]);
$form->end();

Section::end();
