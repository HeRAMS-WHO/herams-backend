<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\search\Project as SearchModelProject;
use prime\widgets\DrilldownColumn;
use prime\widgets\Section;
use SamIT\abac\interfaces\Resolver;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $projectProvider
 * @var SearchModelProject $projectSearch
 * @var Resolver $abacResolver;
 */

$this->title = \Yii::t('app', 'Projects');

Section::begin([
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
        [
            'label' => \Yii::t('app', 'Lead'),
            'value' => static function (Project $project) {
                $usersQuery = $project->getLeads();
                return implode('<br>', ArrayHelper::getColumn($usersQuery->all(), 'name'));
            }
        ]
    ]
]);

Section::end();
