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
                    'class' => 'btn-primary',
                ]
            ],
            [
                'label' => \Yii::t('app', 'Create'),
                'tagName' => 'a',
                'options' => [
                    'href' => \yii\helpers\Url::to(['projects/create']),
                    'class' => 'btn-default',
                ],
                'visible' => app()->user->can('admin')
            ],
        ]
    ]);
    echo \kartik\grid\GridView::widget([
        'caption' => $header,
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                // Just links in the header.
                'linkSelector' => 'th a'
            ]
        ],
        'layout' => "{items}\n{pager}",
        'filterModel' => $projectSearch,
        'dataProvider' => $projectsDataProvider,
        'columns' => [
            [
                'attribute' => 'tool_id',
                'value' => 'tool.acronym',
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filter' => $projectSearch->toolsOptions(),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],

                ],
                'filterInputOptions' => [
                    'placeholder' => \Yii::t('app', 'Select tool')
                ]
            ],
            'title',
            [
                'attribute' => 'country_iso_3',
                'value' => 'country.name',
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filter' => $projectSearch->countriesOptions(),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => \Yii::t('app', 'Select country')
                    ]
                ]
            ],
            'locality_name',
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
                'width' => '100px',
                'template' => '{read} {update} {share} {close}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        $result = Html::a(
                            Html::icon('eye-open'),
                            ['/projects/read', 'id' => $model->id],
                            [
                                'title' => \Yii::t('app', 'Read')
                            ]
                        );
                        return $result;
                    },
                    'update' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\Project $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
                            $result = Html::a(
                                Html::icon('pencil'),
                                ['/projects/update', 'id' => $model->id],
                                [
                                    'title' => \Yii::t('app', 'Update')
                                ]
                            );
                        }
                        return $result;
                    },
                    'share' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\Project $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)) {
                            $result = Html::a(
                                Html::icon('share'),
                                ['/projects/share', 'id' => $model->id],
                                [
                                    'title' => \Yii::t('app', 'Share')
                                ]
                            );
                        }
                        return $result;
                    },
                    'close' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\Project $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
                            $result = Html::a(
                                Html::icon('stop'),
                                ['/projects/close', 'id' => $model->id],
                                [
                                    'data-confirm' => \Yii::t('app', 'Are you sure you want to close project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
                                    'data-method' => 'delete',
                                    'class' => 'text-danger',
                                    'title' => \Yii::t('app', 'Close')
                                ]
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
