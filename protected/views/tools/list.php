<?php

/**
 * @var $toolsDataProvider \yii\data\ActiveDataProvider
 */

use \app\components\Html;

echo \kartik\grid\GridView::widget([
    'dataProvider' => $toolsDataProvider,
    'columns' => [
        'title',
        [
            'attribute' => 'description',
            'format' => 'raw'
        ],
        [
            'attribute' => 'imageUrl',
            'label' => \Yii::t('app', 'Image'),
            'format' => ['image',
                [
                    'height' => '100px',
                ]
            ]
        ],
        'actions' => [
            'class' => \kartik\grid\ActionColumn::class,
            'template' => '{read} {edit}',
            'buttons' => [
                'read' => function($url, $model, $key) {
                    /** @var \prime\models\Tool $model */
                    $result = Html::a(
                        Html::icon('eye-open'),
                        ['tools/read', 'id' => $model->id]
                    );
                    return $result;
                },
                'edit' => function($url, $model, $key) {
                    /** @var \prime\models\Tool $model */
                    $result = '';
                    if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
                        $result = Html::a(
                            Html::icon('pencil'),
                            ['tools/update', 'id' => $model->id]
                        );
                    }
                    return $result;
                },
            ]
        ]
    ]
]);

if(app()->user->identity->isAdmin)
    echo \app\components\Html::a(\Yii::t('app', 'Create'), ['tools/create'], ['class' => 'btn btn-primary']);
?>
