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
use prime\widgets\menu\ProjectTabMenu;
use prime\helpers\Icon;
use yii\helpers\Url;
use yii\helpers\Html;

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
        [
            'label' => \Yii::t('app', 'Download'),
            'icon' => Icon::download_2(),
            'link' => ['project/export', 'id' => $project->id],
            'permission' => Permission::PERMISSION_EXPORT
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
            'class' => FavoriteColumn::class
        ],
        [
            'attribute' => 'id',
        ],
        [
            'label' => 'title',
            'attribute' => 'title',
            'content' => function ($workspace) {
                return (\Yii::$app->user->can(Permission::PERMISSION_SURVEY_DATA, $workspace)) ?
                    Html::a(
                        $workspace->title,
                        ['workspace/limesurvey', 'id' => $workspace->id],
                        [
                            'title' => $workspace->title,
                        ]
                    ) : $workspace->title;
            }
        ],
        [
            'attribute' => 'latestUpdate',
        ],
        [
            'label' => \Yii::t('app', 'contributors'),
            'attribute' => 'contributorCount'
        ],
        [
            'label' => \Yii::t('app', 'health facilities'),
            'attribute' => 'facilityCount',
        ],
        [
            'label' => \Yii::t('app', 'responses'),
            'attribute' => 'responseCount'
        ],
        'actions' => require('workspaces/actions.php')
    ]
]);

\prime\widgets\Section::end();
