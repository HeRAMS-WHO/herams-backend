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
use prime\widgets\menu\WorkspaceTabMenu;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = [
    'label' => $workspace->project->title,
    'url' => ['project/workspaces', 'id' => $workspace->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', "Workspace {workspace}", [
        'workspace' => $workspace->title,
    ]),
    'url' => ['workspaces/limesurvey', 'id' => $workspace->id]
];
$this->title = \Yii::t('app', "Workspace {workspace}", [
    'workspace' => $workspace->title,
]);

echo WorkspaceTabMenu::widget([
    'workspace' => $workspace,
    'currentPage' => $this->context->action->uniqueId
]);

echo Html::beginTag('div', ['class' => "content"]);

echo Html::tag('h4', \Yii::t('app', 'Responses'));
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
