<?php

use app\components\Form;
use app\components\ActiveForm;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Workspace $model
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces for {project}', [
        'project' => $model->project->title
    ]),
    'url' => ['project/workspaces', 'id' => $model->project->id]
];
$this->title = \Yii::t('app', 'New workspace');
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="col-xs-12 col-md-8 col-lg-6 form-content form-bg">
    <h3><?=\Yii::t('app', 'Create Workspace')?></h3>
    <?php
    $form = ActiveForm::begin([
        'id' => 'create-workspace',
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
            'token' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\select2\Select2::class,
                'options' => [
                    'data' => $model->tokenOptions(),
                ],
                'placeholder' => 'Create new token'
            ],

        ]
    ]);
    echo ButtonGroup::widget([
        'options' => [
            'class' => [
                'pull-right'
            ],
        ],
        'buttons' => [
            Html::submitButton(\Yii::t('app', 'Create workspace'), ['class' => 'btn btn-primary']),

        ]
    ]);
    $form->end();
    ?>
</div>
