<?php
/** @var \prime\models\ar\Page $page */
/** @var \prime\models\ar\Element $model */

use app\components\Form;
use kartik\form\ActiveForm;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/update', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => $page->title,
    'url' => ['page/update', 'id' => $page->id]
];

$this->title = \Yii::t('app', 'Create element');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="col-xs-12">
    <?php


    $form = ActiveForm::begin([
        'id' => 'update-page',
        'method' => 'PUT',
        "type" => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'type' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->typeOptions()
            ],
            'sort' => [
                'type' => Form::INPUT_HTML5,
                'html5type' => 'number'
            ],
            'transpose' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'configAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        \yii\bootstrap\Html::submitButton(\Yii::t('app', 'Create element'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ],

        ]

    ]);
    $form->end();

    ?>
</div>