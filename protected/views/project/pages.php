<?php

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 *
 */

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => $model->title,
    'url' => app()->user->can(Permission::PERMISSION_WRITE, $model) ? ['project/update', 'id' => $model->id] : null
];
$this->title = \Yii::t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="form-content form-bg">
    <h3><?=\Yii::t('app', 'Pages')?></h3>
    <?php
    echo GridView::widget([
        'caption' => ButtonGroup::widget([
            'options' => [
                'class' => 'pull-right',
                'style' => [
                    'margin-bottom' => '10px'
                ]
            ],
            'buttons' => [
                [
                    'label' => \Yii::t('app', 'Create page'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['page/create', 'project_id' => $model->id]),
                        'class' => 'btn-primary',
                    ],
                ],
                [
                    'label' => \Yii::t('app', 'Export all'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['project/export-dashboard', 'id' => $model->id]),
                        'download' => true,
                        'class' => 'btn-default',
                    ],
                ],
                [
                    'label' => \Yii::t('app', 'Import pages'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['project/import-dashboard', 'id' => $model->id]),
                        'class' => 'btn-default',
                    ],
                ],
            ]
        ]),
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'title' => [
                'attribute' => 'title',
                'value' => static function (Page $model): string {
                    return \Yii::t('app.pagetitle', $model->title);
                }
            ],

            'parent_id' => [
                'attribute' => 'parent_id',
                'value' => function (Page $model) {
                    return isset($model->parent_id) ? "{$model->parent->title} ({$model->parent_id})" : null;
                }
            ],
            'sort',
            'actions' => [
                'class' => ActionColumn::class,
                'width' => '100px',
                'template' => '{update} {delete}',
                'visibleButtons' => [
                    'update' => function (Page $page) {
                        return app()->user->can(Permission::PERMISSION_WRITE, $page);
                    },
                    'delete' => function (Page $page) {
                        return app()->user->can(Permission::PERMISSION_DELETE, $page);
                    },
                ],
                'buttons' => [
                    'delete' => function ($url, Page $page, $key) {
                        return Html::a(
                            Icon::delete(),
                            [
                                'page/delete',
                                'id' => $page->id
                            ],
                            [
                                'title' => \Yii::t('app', 'Delete'),
                                'data-method' => 'delete',
                                'data-confirm' => \Yii::t('app', 'Are you sure you want to delete this page?')
                            ]
                        );
                    },
                    'update' => function ($url, Page $page, $key) {
                        return Html::a(
                            Icon::edit(),
                            [
                                'page/update',
                                'id' => $page->id
                            ],
                            [
                                'title' => \Yii::t('app', 'Edit')
                            ]
                        );
                    },
                ]
            ]
        ]
    ]);

    ?>
</div>