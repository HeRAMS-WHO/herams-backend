<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
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
        'actions' => include \Yii::getAlias('@views/projects/list/actions.php')
    ]
]);
