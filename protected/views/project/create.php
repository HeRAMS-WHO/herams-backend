<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use kartik\select2\Select2;
use prime\components\View;
use prime\models\ar\Survey;
use prime\models\forms\project\Create;
use prime\objects\enums\ProjectVisibility;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;

/**
 * @var View $this
 * @var Create $model
 */

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
        'note0' => [
            'type' => Form::INPUT_RAW,
            'value' => \Yii::t(
                'app',
                'Select either a {base_survey_eid} or an {admin_survey_id} and {data_survey_id}',
                [
                    'base_survey_eid' => $model->getAttributeLabel('base_survey_eid'),
                    'admin_survey_id' => $model->getAttributeLabel('admin_survey_id'),
                    'data_survey_id' => $model->getAttributeLabel('data_survey_id'),
                ]
            )
        ],
        'base_survey_eid' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => Select2::class,
            'options' => [
                'data' => $model->dataSurveyOptions(),
                'options' => [
                    'placeholder' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ],
        ],
        'admin_survey_id' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => Select2::class,
            'options' => [
                'data' => $model->surveyIdOptions(),
                'options' => [
                    'placeholder' => '',

                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ],
        ],
        'data_survey_id' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => Select2::class,
            'options' => [
                'data' => $model->surveyIdOptions(),
                'options' => [
                    'placeholder' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ],
        ],
        'visibility' => [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => ProjectVisibility::toArray()
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
