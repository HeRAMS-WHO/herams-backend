<?php

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $project
 *
 */

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\widgets\menu\ProjectTabMenu;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $project->title;
$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();

\prime\widgets\Section::begin([
    'header' => \Yii::t('app', 'pages'),
    'actions' => [
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Create page'),
            'link' => ['page/create', 'project_id' => $project->id],
            'style' => 'primary'
        ],
        [
            'icon' => Icon::download_1(),
            'label' => \Yii::t('app', 'Import pages'),
            'link' => ['project/import-dashboard', 'id' => $project->id],
        ],
        [
            'icon' => Icon::export(),
            'label' => \Yii::t('app', 'Export all'),
            'link' => ['project/export-dashboard', 'id' => $project->id],
        ],
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Project dashboard'),
            'link' => ['project/view', 'id' => $project->id],
            'permission' => Permission::PERMISSION_READ,
            'visible' => $project->pageCount > 0
        ]
    ]
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'title' => [
            'attribute' => 'title',
            'value' => static function (Page $project): string {
                return \Yii::t('app.pagetitle', $project->title);
            }
        ],

        'parent_id' => [
            'attribute' => 'parent_id',
            'value' => function (Page $project) {
                return isset($project->parent_id) ? "{$project->parent->title} ({$project->parent_id})" : null;
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
                        Icon::trash(),
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
