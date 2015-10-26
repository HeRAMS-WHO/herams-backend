<?php

use \app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\Project $model
 */

echo \kartik\grid\GridView::widget([
    'dataProvider' =>
        new \yii\data\ActiveDataProvider([
            'query' => $model->getReports()
        ]),
    'columns' => [
        'id',
        'name',
        'published',
        'actions' => [
            'class' => \kartik\grid\ActionColumn::class,
            'template' => '{read}',
            'buttons' => [
                'read' => function($url, $model, $key) {
                    return Html::a(
                        Html::icon('eye-open'),
                        [
                            'reports/read',
                            'id' => $model->id
                        ],
                        [
                            'target' => '_blank'
                        ]
                    );
                }
            ]
        ]
    ]
]);