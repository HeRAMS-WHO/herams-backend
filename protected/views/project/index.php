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
use yii\helpers\Html;
use yii\web\User;
use yii\web\View;
use function iter\func\index;
use function iter\rewindable\map;
use function iter\toArray;

/**
 * @var View $this
 * @var ActiveDataProvider $projectProvider
 * @var SearchModelProject $projectSearch
 * @var Resolver $abacResolver
 * @var User $userComponent
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
            },
            'permission' => Permission::PERMISSION_LIST_WORKSPACES,
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
            'label' => \Yii::t('app', 'Project coordinator'),
            'value' => static function (Project $project) use ($userComponent) {
                return implode(
                    '<br>',
                    ArrayHelper::merge(
                        toArray(map(index('name'), $project->getLeads())),
                        // For now we just don't want to display the link, at some point it is desired to show the link
                        array_filter([false && !$userComponent->can(Permission::PERMISSION_ADMIN, $project) ? Html::tag('i', Html::a(\Yii::t('app', 'Request access'), ['project/request-access', 'id' => $project->id])) : null])
                    )
                );
            },
            'format' => 'html',
        ],
    ]
]);

Section::end();
