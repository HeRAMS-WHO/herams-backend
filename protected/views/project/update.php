<?php

/** @var \prime\models\ar\Project $model */

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use app\components\Form;
use prime\helpers\Icon;
use prime\models\ar\Setting;
use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;


$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'method' => 'PUT',
        "type" => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'base_survey_eid' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->dataSurveyOptions(),
            ],
            'latitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'longitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'status' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->statusOptions()
            ],
            'typemapAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],
            'overridesAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Update project'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ],

        ]
    ]);
    $form->end();

    ?>
</div>
<div class="col-xs-12">
    <?php
        echo GridView::widget([
            'caption' => ButtonGroup::widget([
                'options' => [
                    'class' => 'pull-right',
                    'style' => [
                        'margin-bottom' => '10px'
                    ]
                ],
                'buttons' => [
                    [
                        'label' => \Yii::t('app', 'Create page'),
                        'tagName' => 'a',
                        'options' => [
                            'href' => Url::to(['page/create', 'project_id' => $model->id]),
                            'class' => 'btn-default',
                        ],
                    ],
                ]
            ]),
            'dataProvider' => new ActiveDataProvider(['query' => $model->getAllPages()]),
            'columns' => [
                'id',
                'title',
                'parent_id' => [
                    'value' => function(\prime\models\ar\Page $model) {
                        return isset($model->parent_id) ? "{$model->parent->title} ({$model->parent_id})" : null;
                    }
                ],
                'actions' => [
                    'class' => \kartik\grid\ActionColumn::class,
                    'width' => '100px',
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function($url, $model, $key) {
                            /** @var \prime\models\ar\Page $model */
                            $result = '';
                            if(app()->user->can('admin')) {
                                $result = Html::a(
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