<?php

use kartik\widgets\ActiveForm;
use app\components\Form;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

$this->title = Yii::t('app', 'Update workspace');
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Back to project overview'),
    'url' => ['projects/overview', 'pid' => $model->tool_id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Manage workspaces'),
    'url' => ['projects/list', 'toolId' => $model->tool_id]
];


?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'update-project',
        'method' => 'PUT',
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
            'token' => [
                'type' => Form::INPUT_STATIC
            ],
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'owner_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->ownerOptions()
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
            Html::a(\Yii::t('app', 'Edit token'), ['projects/configure', 'id' => $model->id], [
                'class' => ['btn btn-default']
            ]),
            Html::submitButton(\Yii::t('app', 'Save'), ['form' => 'update-project', 'class' => 'btn btn-primary']),

        ]
    ]);
    $form->end();
    ?>
</div>
