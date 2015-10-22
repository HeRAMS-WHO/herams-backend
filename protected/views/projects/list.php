<?php

/**
 * @var $projectsDataProvider \yii\data\ActiveDataProvider
 */

use \app\components\Html;

echo \kartik\grid\GridView::widget([
    'dataProvider' => $projectsDataProvider,
    'columns' => [
        'title',
        [
            'attribute' => 'description',
            'format' => 'raw'
        ],
        'tool.title',
        'actions' => [
            'class' => \kartik\grid\ActionColumn::class,
            'template' => '{read}',
            'buttons' => [
                'read' => function($url, $model, $key) {
                    $result = Html::a(
                        Html::icon('eye-open'),
                        ['projects/read', 'id' => $model->id]
                    );
                    return $result;
                }
            ]
        ]
    ]
]);

if(app()->user->identity->isAdmin)
    echo \app\components\Html::a(\Yii::t('app', 'Create'), ['projects/create'], ['class' => 'btn btn-primary']);
?>
