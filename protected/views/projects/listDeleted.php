<?php

use \app\components\Html;
use prime\models\ar\Setting;

/**
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 * @var \prime\models\search\Project $projectSearch
 */

?>
<div class="col-xs-12">
    <?php

//    \yii\bootstrap\Button::class
    $header = Yii::t('app', 'Closed projects')
        .
        \yii\bootstrap\ButtonGroup::widget([
            'options' => [
                'class' => 'pull-right',
                'style' => ['margin-right' => '10px']
            ],
            'buttons' => [
                [
                    'label' => \Yii::t('app', 'Show open projects'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => \yii\helpers\Url::to(['/projects/list']),
                        'class' => 'btn-default',
                    ]
                ],
            ]
        ])
    ;
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
                'attribute' => 'id'
            ],
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
            [
                'attribute' => 'closed',
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
                'template' => '{read} {open}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        $result = Html::a(
                            Html::icon(Setting::get('icons.read')),
                            ['/projects/read', 'id' => $model->id],
                            [
                                'title' => \Yii::t('app', 'Read')
                            ]
                        );
                        return $result;
                    },
                    'open' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\Project $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.open')),
                                ['/projects/re-open', 'id' => $model->id],
                                [
                                    'data-confirm' => \Yii::t('app', 'Are you sure you want to re-open project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
                                    'data-method' => 'put',
                                    'class' => 'text-danger',
                                    'title' => \Yii::t('app', 'Re-open')
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
