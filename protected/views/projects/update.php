<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

$this->title = Yii::t('app', 'Update project');

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => \Yii::t('app', 'Edit token'),
            'url' => ['projects/configure', 'id' => $model->id],
        ],
        '<li>' . Html::submitButton(\Yii::t('app', 'Save'), ['form' => 'update-project', 'class' => 'btn btn-primary']) . '</li>'
    ]
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
    $form->end();
    ?>
</div>