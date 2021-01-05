<?php

use app\components\Form;
use app\components\ActiveForm;
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

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'New workspace'),
    'url' => ['workspace/create', 'project_id' => $model->project->id]
];
$this->title = \Yii::t('app', 'New workspace');

echo Html::beginTag('div', ['class' => 'content no-tab']);

?>

<div class="form-content form-bg">
    <h4><?=\Yii::t('app', 'Create Workspace')?></h4>
    <?php
    $form = ActiveForm::begin([
        'id' => 'create-workspace',
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

<?php
echo Html::endTag('div');
?>