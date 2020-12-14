<?php

/**
 * @var \yii\data\ActiveDataProvider $workspacesDataProvider
 * @var \prime\models\search\Workspace $workspaceSearch
 * @var int $closedCount
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $project
 *
 */

use kartik\grid\GridView;
use prime\models\ar\Permission;
use prime\widgets\FavoriteColumn\FavoriteColumn;
use prime\widgets\menu\TabMenu;
use yii\helpers\Url;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/workspaces', 'id' => $project->id]
];
$this->title = $project->title;


echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);



$tabs = [
    [
        'url' => ['project/workspaces', 'id' => $project->id],
        'title' => \Yii::t('app', 'Workspaces'),
        'class' => 'active'
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project)) {
    $tabs[] =     [
        'url' => ['project/pages', 'id' => $project->id],
        'title' => \Yii::t('app', 'dashboard')
    ];
    $tabs[] = [
        'url' => ['project/update', 'id' => $project->id],
        'title' => \Yii::t('app', 'Settings')
    ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $project)) {
    $tabs[] = [
        'url' => ['project/share', 'id' => $project->id],
        'title' => \Yii::t('app', 'Share')
    ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->id
]);



echo Html::beginTag('div', ['class' => "content"]);
echo Html::beginTag('div', ['class' => 'action-group']);
if (app()->user->can(Permission::PERMISSION_MANAGE_WORKSPACES, $project)) {
    echo Html::a(\Yii::t('app', 'Import workspaces'), Url::to(['workspace/import', 'project_id' => $project->id]), ['class' => 'btn btn-default']);
    echo Html::a(\Yii::t('app', 'Create workspace'), Url::to(['workspace/create', 'project_id' => $project->id]), ['class' => 'btn btn-primary']);
}
echo Html::endTag('div');

echo GridView::widget([
    'pjax' => true,
    'pjaxSettings' => [
        'options' => [
            // Just links in the header.
            'linkSelector' => 'th a'
        ]
    ],
    'layout' => "{items}\n{pager}",
    'filterModel' => $workspaceSearch,
    'dataProvider' => $workspaceProvider,
    'columns' => [
        [
            'class' => FavoriteColumn::class
        ],
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'title'
        ],
        [
            'attribute' => 'latestUpdate',
        ],
        [
            'attribute' => 'contributorCount'
        ],
        [
            'attribute' => 'facilityCount',
        ],
        [
            'attribute' => 'responseCount'
        ],
        'actions' => require('workspaces/actions.php')
    ]
]);

echo Html::endTag('div');
echo Html::endTag('div');
