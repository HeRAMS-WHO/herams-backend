<?php
/** @var \prime\models\ar\Page $page */
    use app\components\Form;
    use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use prime\helpers\Icon;
use yii\data\ActiveDataProvider;

$this->title = Yii::t('app', 'Update element');
$this->params['breadcrumbs'] = [
    [
        'label' => \Yii::t('app', "Update project: {project}", [
            'project' => $page->project->title,
        ]),
        'url' => ['/project/update', 'id' => $page->project->id]
    ],
    [
        'label' => \Yii::t('app', "Update page: {page}", [
                'page' => $page->title,
        ]),
    ],
];

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
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Update project'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ]
        ]
    ]);
    $form->end();

    ?>
</div>
<div class="col-xs-12">
    <?php
    echo GridView::widget([
        'dataProvider' => new ActiveDataProvider(['query' => $page->getElements()]),
        'columns' => [
            'id',
            'type',
            'sort',
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'width' => '100px',
                'template' => '{update}',
                'buttons' => [
                    'update' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Page $model */
                        $result = '';
                        if(app()->user->can('admin')) {
                            $result = \yii\bootstrap\Html::a(
                                Icon::pencilAlt(),
                                ['page/update', 'id' => $model->id], [
                                    'title' => \Yii::t('app', 'Edit')
                                ]
                            );
                        }
                        return $result;
                    },
                ]
            ]
        ]
    ]);

    ?>
</div>