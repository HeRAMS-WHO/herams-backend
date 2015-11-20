<?php

use \app\components\Html;

/**
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 * @var \prime\models\search\Project $projectSearch
 */

?>
<div class="col-xs-12">
    <?php

//    \yii\bootstrap\Button::class
    $header = Yii::t('app', 'Your projects') . \yii\bootstrap\ButtonGroup::widget([
        'options' => [
            'class' => 'pull-right'
        ],
        'buttons' => [
            [
                'label' => 'New project',
                'tagName' => 'a',
                'options' => [
                    'href' => \yii\helpers\Url::to(['projects/new']),
                    'class' => 'btn-primary'
                ]
            ],
            [
                'label' => \Yii::t('app', 'Create'),
                'tagName' => 'a',
                'options' => [
                    'href' => \yii\helpers\Url::to(['projects/create']),
                    'class' => 'btn-default'
                ],
                'visible' => app()->user->can('admin')
            ],
        ]
    ]);
    echo \kartik\grid\GridView::widget([
        'caption' => $header,
        'layout' => "{items}\n{pager}",
        'filterModel' => $projectSearch,
        'dataProvider' => $projectsDataProvider,
        'columns' => [
            'title',
            [
                'attribute' => 'description',
                'format' => 'raw'
            ],
            [
                'attribute' => 'countriesIds',
                'value' => 'locality',
                'label' => \Yii::t('app', 'Country'),
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filter' => $projectSearch->countriesOptions(),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => \Yii::t('app', 'Select country')
                    ]
                ]
            ],
            [
                'attribute' => 'toolIds',
                'value' => 'tool.acronym',
                'label' => \Yii::t('app', 'Tool'),
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filter' => $projectSearch->toolsOptions(),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => \Yii::t('app', 'Select tool')
                    ]
                ]
            ],
            [
                'attribute' => 'created',
                'format' => 'date',
                'filterType' => \kartik\grid\GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'YYYY-MM-DD',
                        ],
                        'allowClear'=>true,
                    ],
                    'pluginEvents' => [
                        "apply.daterangepicker" => "function() { $('.grid-view').yiiGridView('applyFilter'); }"
                    ]
                ],
            ],
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'template' => '{read} {update}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        $result = Html::a(
                            Html::icon('eye-open'),
                            ['/projects/read', 'id' => $model->id]
                        );
                        return $result;
                    },
                    'update' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\Project $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
                            $result = Html::a(
                                Html::icon('pencil'),
                                ['/projects/update', 'id' => $model->id]
                            );
                        }
                        return $result;
                    }
                ]
            ]
        ]
    ]);
    ?>
</div>
