<?php
/** @var \prime\models\ar\Page $page */
    use app\components\Form;
    use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use prime\helpers\Icon;
use yii\data\ActiveDataProvider;

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

$this->title = \Yii::t('app', 'Create page');
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
        'model' => $page,
        'columns' => 1,
        "attributes" => [
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'parent_id' => [
                'attribute' => 'parent_id' ,
                'type' => Form::INPUT_DROPDOWN_LIST,

                'items' => array_merge(['' => 'No parent'], $page->parentOptions())
            ],
            'sort' => [
               'type' => Form::INPUT_TEXT,
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Create page'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ]
        ]
    ]);
    $form->end();

    ?>
</div>