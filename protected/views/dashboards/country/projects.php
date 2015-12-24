<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 */

echo \kartik\grid\GridView::widget([
    'layout' => "{items}\n{pager}",
    'dataProvider' => $projectsDataProvider,
    'columns' => [
        [
            'attribute' => 'tool_id',
            'value' => 'tool.acronym',
        ],
        'title',
        [
            'attribute' => 'country_iso_3',
            'value' => 'country.name',
        ],
        'locality_name',
        [
            'attribute' => 'created',
            'format' => 'date',
        ],
        'actions' => [
            'class' => \kartik\grid\ActionColumn::class,
            'width' => '100px',
            'template' => '{read}',
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
                }
            ]
        ]
    ]
]);