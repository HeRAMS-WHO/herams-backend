<?php

/**
 * @var $toolsDataProvider \yii\data\ActiveDataProvider
 */

use \app\components\Html;

$this->params['subMenu']['items'] = [
    [
        'label' => \Yii::t('app', 'Create'),
        'url' => ['tools/create'],
        'visible' => app()->user->identity->isAdmin
    ]
];

?>
<div class="col-xs-12">
    <?php
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $toolsDataProvider,
        'columns' => [
            'acronym',
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
            [
                'attribute' => 'thumbnailUrl',
                'label' => \Yii::t('app', 'Thumbnail'),
                'format' => ['image',
                    [
                        'height' => '32px',
                    ]
                ]
            ],
            [
                'attribute' => 'generators',
                'value'=>function ($model, $key, $index, $widget) {
                    return Html::ul($model->generators);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'projectCount',
                'format' => 'integer'
            ],
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'template' => '{read} {edit} {remove}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = Html::a(
                            Html::icon('eye-open'),
                            ['tools/read', 'id' => $model->id]
                        );
                        return $result;
                    },
                    'edit' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('admin')) {
                            $result = Html::a(
                                Html::icon('pencil'),
                                ['tools/update', 'id' => $model->id]
                            );
                        }
                        return $result;
                    },

                    'remove' => function($url, \prime\models\ar\Tool $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('admin') && $model->getProjectCount() == 0) {
                            $result = Html::a(
                                Html::icon('trash'),
                                ['tools/delete', 'id' => $model->id],
                                [
                                    'data-method' => 'delete',
                                    'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this tool from the system?')
                                ]
                            );
                        }
                        return $result;
                    },
                    'deactivate' => function($url, \prime\models\ar\Tool $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('admin') && $model->getProjectCount() > 0) {
                            $result = Html::a(
                                Html::icon('stop'),
                                ['tools/delete', 'id' => $model->id],
                                [
                                    'data-method' => 'delete',
                                    'data-confirm' => \Yii::t('app', 'Are you sure you wish to disable this tool?')
                                ]
                            );
                        }
                        return $result;
                    },
                ]
            ]
        ]
    ]);
    ?>
</div>