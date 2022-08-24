<?php

declare(strict_types=1);

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 * @var Project $project
 */

$this->title = \Yii::t('app', "Dashboard settings for {project}", ['project' => $project->title]);

$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();

Section::begin([
    'actions' => [
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Create page'),
            'link' => [
                'page/create',
                'project_id' => $project->id,
            ],
            'style' =>
'primary',
        ],
        [
            'icon' => Icon::download_1(),
            'label' => \Yii::t('app', 'Import pages'),
            'link' => [
                'project/import-dashboard',
                'id' => $project->id,
            ],
        ],
        [
            'icon' => Icon::export(),
            'label' => \Yii::t('app', 'Export all'),
            'link' => [
                'project/export-dashboard',
                'id' => $project->id,
            ],
        ],
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Project dashboard'),
            'link' => [
                'project/view',
                'id' => $project->id,
            ],
            'permission' => Permission::PERMISSION_READ,
            'visible' => $project->
pageCount >
0,
        ],
    ],
])->withHeader(\Yii::t('app', 'pages'));

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'title' => [
            'attribute' => 'title',
            'value' => static function (Page $page): string {
                return \Yii::t('app.pagetitle', $page->title);
            },
        ],

        'parent_id' => [
            'attribute' => 'parent_id',
            'value' => function (Page $project) {
                return isset($project->parent_id) ? \Yii::t('app.pagetitle', $project->parent->title) . " ({$project->parent_id})" : null;
            },
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
                            'id' => $page->id,
                        ],
                        [
                            'title' => \Yii::t('app', 'Delete'),
                            'data-method' => 'delete',
                            'data-confirm' => \Yii::t('app', 'Are you sure you want to delete this page?'),
                        ]
                    );
                },
                'update' => function ($url, Page $page, $key) {
                    return Html::a(
                        Icon::edit(),
                        [
                            'page/update',
                            'id' => $page->id,
                        ],
                        [
                            'title' => \Yii::t('app', 'Edit'),
                        ]
                    );
                },
            ],
        ],
    ],
]);

Section::end();
