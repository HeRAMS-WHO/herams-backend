<?php

/**
 * @var $toolsDataProvider \yii\data\ActiveDataProvider
 */

use \app\components\Html;

echo \kartik\grid\GridView::widget([
    'dataProvider' => $toolsDataProvider,
    'columns' => [
        'title',
        'description',
        'actions' => [
            'class' => \kartik\grid\ActionColumn::class,
            'template' => '{read}',
            'buttons' => [
                'read' => function($url, $model, $key) {
                    $result = Html::a(
                        Html::icon('eye-open'),
                        ['tools/read', 'id' => $model->id]
                    );
                    return $result;
                }
            ]
        ]
    ]
]);

if(app()->user->identity->isAdmin)
    echo \app\components\Html::a(\Yii::t('app', 'Create'), ['tools/create'], ['class' => 'btn btn-primary']);
?>
