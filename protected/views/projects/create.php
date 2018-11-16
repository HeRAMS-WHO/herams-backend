<?php

use kartik\widgets\ActiveForm;
use app\components\Form;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

$this->title = Yii::t('app', 'Create workspace');
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Back to project overview'),
    'url' => ['projects/overview', 'pid' => $tool->id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Manage workspaces'),
    'url' => ['projects/list', 'toolId' => $tool->id]
];



?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'create-project',
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
            'owner_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->ownerOptions()
            ],
            'token' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $tool->tokenOptions()
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Create workspace'), ['form' => 'create-project', 'class' => 'btn btn-primary'])
                    ]
                ])
            ]
        ]
    ]);
    $form->end();
    ?>
</div>
