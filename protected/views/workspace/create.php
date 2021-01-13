<?php

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use prime\helpers\Icon;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Workspace $model
 */

$this->params['breadcrumbs'][] = [
    'label' => $model->project->title,
    'url' => ['project/workspaces', 'id' => $model->project->id]
];

$this->title = \Yii::t('app', 'New workspace');

$form = ActiveForm::begin([
    'id' => 'create-workspace',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'labelSpan' => 3
    ]
]);

echo Form::widget([
    'form' => $form,
    'model' => $model,
    'columns' => 1,
    "attributes" => [
        'title' => [
            'type' => Form::INPUT_TEXT,
        ],
        'token' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\select2\Select2::class,
            'options' => [
                'data' => $model->tokenOptions(),
            ],
        ],
        FormButtonsWidget::embed([
                'buttons' => [
                    Html::submitButton(\Yii::t('app', 'Create workspace'), ['class' => 'btn btn-primary']),
                ]
        ])

    ]
]);
$form->end();
