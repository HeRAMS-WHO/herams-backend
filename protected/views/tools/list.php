<?php

use \app\components\Html;
use prime\models\ar\Setting;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $toolsDataProvider
 */

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
                'template' => '{read} {dashboard} {edit} {share} {remove}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = Html::a(
                            Html::icon(Setting::get('icons.read')),
                            ['tools/read', 'id' => $model->id]
                        );
                        return $result;
                    },
                    'dashboard' => function($url, $model, $key) {
                        $result = '';

                        /** @var \prime\models\ar\Tool $model */
                        if(app()->user->can('admin')) {
                            $result = Html::a(
                                Html::icon('thumbs-up'),
                                ['tools/dashboard', 'id' => $model->id]
                            );
                        }
                        return $result;
                    },
                    'edit' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('admin')) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.update')),
                                ['tools/update', 'id' => $model->id]
                            );
                        }
                        return $result;
                    },
                    'share' => function($url, \prime\models\ar\Tool $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('share', ['model' => \prime\models\ar\Tool::class, 'modelId' => $model->primaryKey])) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.share')),
                                ['tools/share', 'id' => $model->id]
                            );
                        }
                        return $result;
                    },
                    'remove' => function($url, \prime\models\ar\Tool $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('admin') && $model->getProjectCount() == 0) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.remove')),
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
                                Html::icon(Setting::get('icons.stop')),
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