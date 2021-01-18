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
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\widgets\FavoriteColumn\FavoriteColumn;
use prime\widgets\menu\ProjectTabMenu;

$this->title = $project->title;
$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();

\prime\widgets\Section::begin([
    'subject' => $project,
    'actions' => [
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Create workspace'),
            'link' => ['workspace/create', 'project_id' => $project->id],
            'permission' => Permission::PERMISSION_MANAGE_WORKSPACES
        ],
        [
            'icon' => Icon::download_1(),
            'label' => \Yii::t('app', 'Import workspaces'),
            'link' => ['workspace/import', 'project_id' => $project->id],
            'permission' => Permission::PERMISSION_MANAGE_WORKSPACES
        ],
    ]
]);
echo GridView::widget([
    'pjax' => true,
    'export' => false,
    'pjaxSettings' => [
        'options' => [
            // Just links in the header.
            'linkSelector' => 'th a'
        ]
    ],
    //'layout' => "{items}\n{pager}",
    'filterModel' => $workspaceSearch,
    'dataProvider' => $workspaceProvider,
    'columns' => [
        [
            'class' => FavoriteColumn::class,
            'filter' => [1 => 'Yes', 0 => 'No']
        ],
        [
            'class' => \prime\widgets\IdColumn::class
        ],
        [
            'class' => \prime\widgets\DrilldownColumn::class,
            'attribute' => 'title',
            'permission' => Permission::PERMISSION_SURVEY_DATA,
            'link' => function ($workspace) {
                return ['workspace/limesurvey', 'id' => $workspace->id];
            }
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
        ]
    ]
]);

\prime\widgets\Section::end();
