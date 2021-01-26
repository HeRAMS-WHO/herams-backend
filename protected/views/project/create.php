<?php
declare(strict_types=1);

use app\components\Form;
use app\components\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var \prime\models\ar\Project $model
 * @var \prime\components\View $this
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

    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3
        ]
    ]);
    echo \app\components\Form::widget([
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
            \prime\widgets\FormButtonsWidget::embed([
                'buttons' =>  [
                    Html::submitButton(\Yii::t('app', 'Create project'), ['class' => 'btn btn-primary'])
                ]
            ])
        ],
    ]);
    $form->end();
