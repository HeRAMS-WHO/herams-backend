<?php

/**
 * @var \yii\data\ActiveDataProvider $responseProvider
 * @var \prime\models\search\Response $responseSearch
 * @var int $closedCount
 * @var \yii\web\View $this
 * @var \prime\models\ar\Workspace $workspace
 *
 */

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use yii\bootstrap\ButtonGroup;
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
    'label' => $workspace->project->title,
    'url' => app()->user->can(Permission::PERMISSION_WRITE, $workspace->project) ? ['project/update', 'id' => $workspace->project->id] : null
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces'),
    'url' => ['/project/workspaces', 'id' => $workspace->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $workspace->title,
    'url' => app()->user->can(Permission::PERMISSION_WRITE, $workspace) ? ['workspace/update', 'id' => $workspace->id] : null

];
$this->title = \Yii::t('app', 'Responses');
//$this->params['breadcrumbs'][] = $this->title;



echo Html::beginTag('div', ['class' => 'topbar']);
echo Html::beginTag('div', ['class' => 'pull-left']);

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::list();
echo Html::tag('span', \Yii::t('app', 'Health Facilities'));
echo Html::tag('em', $workspace->facilityCount);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::contributors();
echo Html::tag('span', \Yii::t('app', 'Contributors'));
echo Html::tag('em', $workspace->contributorCount);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::recycling();
echo Html::tag('span', \Yii::t('app', 'Latest update'));
echo Html::tag('em', $workspace->latestUpdate);
echo Html::endTag('div');

echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'btn-group pull-right']);
echo Html::a(Icon::project(), ['project/view', 'id' => $workspace->project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

echo GridView::widget([
    'caption' => ButtonGroup::widget([
        'options' => [
            'class' => 'pull-right',
        ],
        'buttons' => [
//            [
//                'label' => \Yii::t('app', 'Import workspaces'),
//                'tagName' => 'a',
//                'options' => [
//                    'href' => Url::to(['workspace/import', 'project_id' => $project->id]),
//                    'class' => 'btn-default',
//                ],
//                'visible' => app()->user->can(Permission::PERMISSION_MANAGE_WORKSPACES, $project)
//            ],
//            [
//                'label' => \Yii::t('app', 'Create workspace'),
//                'tagName' => 'a',
//                'options' => [
//                    'href' => Url::to(['workspace/create', 'project_id' => $project->id]),
//                    'class' => 'btn-primary',
//                ],
//                'visible' => app()->user->can(Permission::PERMISSION_MANAGE_WORKSPACES, $project)
//            ],

        ]
    ]),
    'pjax' => true,
    'pjaxSettings' => [
        'options' => [
            // Just links in the header.
            'linkSelector' => 'th a'
        ]
    ],
    'layout' => "{items}\n{pager}",
    'filterModel' => $responseSearch,
    'dataProvider' => $responseProvider,
    'columns' => [
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'date'
        ],
        [
            'attribute' => 'hf_id',
        ],
        [
            'attribute' => 'last_updated'
        ],
//        [
//            'label' => '# Health facilities',
//            'attribute' => 'facilityCount',
//        ],
//        [
//            'label' => '# Responses',
//            'value' => 'responseCount'
//        ],
//        [
//            'class' => \prime\widgets\FavoriteColumn\FavoriteColumn::class
//        ],
        'actions' => [
            'class' => ActionColumn::class,
            'width' => '150px',
            'controller' => 'response',
            'template' => '{compare}',
            'buttons' => [
                'compare' => function ($url, \prime\models\ar\Response $model, $key) {
                    $result = Html::a(Icon::eye(), $url, [
                        'title' => \Yii::t('app', 'Refresh data from limesurvey')
                    ]);
                    return $result;
                },
            ]
        ]
    ]
]);

echo Html::endTag('div');
