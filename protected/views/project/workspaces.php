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
use prime\helpers\Icon;
use yii\helpers\Url;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => app()->user->can(Permission::PERMISSION_WRITE, $project) ? ['project/update', 'id' => $project->id] : null
];
$this->title = \Yii::t('app', 'Workspaces');
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('div', ['class' => 'topbar']);
echo Html::beginTag('div', ['class' => 'pull-left']);
echo Html::beginTag('div', ['class' => 'count']);
echo Icon::healthFacility();
echo Html::tag('span', \Yii::t('app', 'Health Facilities'));
echo Html::tag('em', $workspaceProvider->getTotalCount());
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'btn-group']);
if (app()->user->can(Permission::PERMISSION_MANAGE_WORKSPACES, $project)) {
    echo Html::a(\Yii::t('app', 'Import workspaces'), Url::to(['workspace/import', 'project_id' => $project->id]), ['class' => 'btn btn-default']);
    echo Html::a(\Yii::t('app', 'Create workspace'), Url::to(['workspace/create', 'project_id' => $project->id]), ['class' => 'btn btn-primary']);
}
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'btn-group pull-right']);
echo Html::a(Icon::project(), ['project/view', 'id' => $project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);
echo GridView::widget([
    /*'caption' => ButtonGroup::widget([
            'options' => [
                'class' => 'pull-right',
            ],
            'buttons' => [
                [
                    'label' => \Yii::t('app', 'Import workspaces'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['workspace/import', 'project_id' => $project->id]),
                        'class' => 'btn-default',
                    ],
                    'visible' => app()->user->can(Permission::PERMISSION_MANAGE_WORKSPACES, $project)
                ],
                [
                    'label' => \Yii::t('app', 'Create workspace'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['workspace/create', 'project_id' => $project->id]),
                        'class' => 'btn-primary',
                    ],
                    'visible' => app()->user->can(Permission::PERMISSION_MANAGE_WORKSPACES, $project)
                ],

            ]
        ]),*/
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
        [
            'class' => FavoriteColumn::class
        ],
        'actions' => require('workspaces/actions.php')
    ]
]);

echo Html::endTag('div');
