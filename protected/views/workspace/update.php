<?php

use app\components\Form;
use app\components\ActiveForm;
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
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces for {project}', [
        'project' => $model->project->title
    ]),
    'url' => ['project/workspaces', 'id' => $model->project->id]
];
$this->title = \Yii::t('app', 'Update workspace {workspace}', ['workspace' => $model->title]);
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="col-xs-12 col-md-8 col-lg-6 form-bg">
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
        ]
    ]);
    echo \yii\bootstrap\ButtonGroup::widget([
        'options' => [
            'class' => [
                'pull-right'
            ],
        ],
        'buttons' => [
            Html::a(\Yii::t('app', 'Edit token'), ['workspace/configure', 'id' => $model->id], [
                'class' => ['btn btn-default']
            ]),
            Html::submitButton(\Yii::t('app', 'Save'), ['form' => 'update-project', 'class' => 'btn btn-primary']),

        ]
    ]);
    $form->end();
    ?>
</div>
