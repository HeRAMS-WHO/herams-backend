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
use prime\widgets\menu\TabMenu;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/workspaces', 'id' => $project->id]
];
$this->title = $project->title;


echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

$tabs = [
    [
        'url' => ['project/workspaces', 'id' => $project->id],
        'title' => \Yii::t('app', 'Workspaces') . " ({$project->workspaceCount})"
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project)) {
    $tabs[] =
        [
            'url' => ['project/pages', 'id' => $project->id],
            'title' => \Yii::t('app', 'Dashboard settings')
        ];
    $tabs[] =
        [
            'url' => ['project/update', 'id' => $project->id],
            'title' => \Yii::t('app', 'Project settings')
        ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $project)) {
    $tabs[] =
        [
            'url' => ['project/share', 'id' => $project->id],
            'title' => \Yii::t('app', 'Users') . " ({$project->contributorCount})"
        ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project)) {
    $tabs[] =
        [
            'url' => ['/admin/limesurvey'],
            'title' => \Yii::t('app', 'Backend administration')
        ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->uniqueId
]);
echo Html::beginTag('div', ['class' => count($tabs) <= 1 ? 'content no-tab': 'content']);

echo Html::beginTag('div', ['class' => "form-content form-bg full-width"]);

echo Html::beginTag('div', ['class' => 'action-group']);
echo Html::a(Icon::add() . \Yii::t('app', 'Create page'), Url::to(['page/create', 'project_id' => $project->id]), ['class' => 'btn btn-primary btn-icon']);
echo Html::a(Icon::download_1() . \Yii::t('app', 'Import pages'), Url::to(['project/import-dashboard', 'id' => $project->id]), ['class' => 'btn btn-default btn-icon']);
echo Html::a(Icon::export() . \Yii::t('app', 'Export all'), Url::to(['project/export-dashboard', 'id' => $project->id]), ['class' => 'btn btn-default btn-icon']);
if ($project->pageCount > 0 && app()->user->can(Permission::PERMISSION_READ, $project)) {
    echo Html::a(
        Icon::project() . \Yii::t('app', 'Project dashboard'),
        ['project/view', 'id' => $project->id],
        [
            'title' => \Yii::t('app', 'Project dashboard'),
            'class' => 'btn btn-default btn-icon'
        ]
    );
}

echo Html::endTag('div');

echo '<h4>'.\Yii::t('app', 'Dashboard pages').'</h4>';

echo GridView::widget([
    'caption' => ButtonGroup::widget([
        'options' => [
            'class' => 'pull-right',
            'style' => [
                'margin-bottom' => '10px'
            ]
        ]
    ]),
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

echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
