<?php
declare(strict_types=1);

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\widgets\DrilldownColumn;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $projectProvider
 * @var \prime\models\search\Project $projectSearch
 */

$this->title = \Yii::t('app', 'Projects');

\prime\widgets\Section::begin([
    'actions' => [
        [
            'label' => \Yii::t('app', 'Create project'),
            'link' => ['project/create'],
            'style' => 'primary',
            'icon' => Icon::add(),
            'permission' => Permission::PERMISSION_CREATE_PROJECT
        ]
    ]
])->withHeader($this->title);

echo GridView::widget([
    'pjax' => true,
    'pjaxSettings' => [
        'options' => [
            // Just links in the header.
            'linkSelector' => 'th a'
        ]
    ],
    'dataProvider' => $projectProvider,
    'filterModel' => $projectSearch,
    'columns' => [
        'id',
        [
            'attribute' => 'title',
            'class' => DrilldownColumn::class,
            'link' => static function ($project) {
                return ['project/workspaces', 'id' => $project->id];
            }
        ],
        [
            'label' => \Yii::t('app', '# Workspaces'),
            'attribute'  => 'workspaceCount',
        ],
        [
            'label' => \Yii::t('app', '# Contributors'),
            'attribute' => 'contributorCount'
        ],
        [
            'label' => \Yii::t('app', '# Health facilities'),
            'attribute' => 'facilityCount'
        ],
        [
            'label' => \Yii::t('app', '# Responses'),
            'attribute' => 'responseCount'
        ],

    ]
]);

\prime\widgets\Section::end();
