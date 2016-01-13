<?php

use \app\components\Html;

/**
 * @var \prime\models\search\Report $reportSearch
 * @var \yii\data\ActiveDataProvider $reportsDataProvider
 */

?>
<div class="col-xs-12">
    <?php

    echo \kartik\grid\GridView::widget([
        'caption' => Yii::t('app', 'Reports'),
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                // Just links in the header.
                'linkSelector' => 'th a'

            ]
        ],

        'layout' => "{items}\n{pager}",
        'filterModel' => $reportSearch,
        'dataProvider' => $reportsDataProvider,
        'columns' => [
            [
                'attribute' => 'toolId',
                'value' => 'tool.acronym',
                'label' => \Yii::t('app', 'Tool'),
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filter' => $reportSearch->toolsOptions(),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => \Yii::t('app', 'Select tool')
                    ]
                ]
            ],
            [
                'attribute' => 'title',
                'filterType' => \kartik\grid\GridView::TEXT,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear'=>true,
                    ],
                ]
            ],
            [
                'attribute' => 'countryId',
                'value' => 'project.country.name',
                'label' => \Yii::t('app', 'Country'),
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filter' => $reportSearch->countriesOptions(),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => \Yii::t('app', 'Select country')
                    ]
                ]
            ],
            [
                'attribute' => 'localityName',
                'value' => 'project.locality_name',
                'label' => \Yii::t('app', 'Locality')
            ],
            [
                'attribute' => 'published',
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
                'label' => \Yii::t('app', 'Type'),
                'attribute' => 'file.mime_type'
            ],
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'width' => '100px',
                'template' => '{read}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        $result = Html::a(
                            Html::icon('eye-open'),
                            ['/reports/read', 'id' => $model->id],
                            [
                                'title' => \Yii::t('app', 'Read')
                            ]
                        );
                        return $result;
                    }
                ]
            ]
        ]
    ]);
    ?>
</div>
