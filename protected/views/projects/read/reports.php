<?php

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
        'published'
    ]
]);