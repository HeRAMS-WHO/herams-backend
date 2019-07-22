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
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Url;


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


?>
<div class="col-xs-12">
    <?php


    echo GridView::widget([
        'caption' => ButtonGroup::widget([
            'options' => [
                'class' => 'pull-right',
                'style' => [
                    'margin-bottom' => '10px'
                ]
            ],
            'buttons' => [
                [
                    'label' => \Yii::t('app', 'Import workspaces'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['workspace/import', 'project_id' => $project->id]),
                        'class' => 'btn-default',
                    ],
                    'visible' => app()->user->can(Permission::PERMISSION_WRITE, $project)
                ],
                [
                    'label' => \Yii::t('app', 'Create workspace'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['workspace/create', 'project_id' => $project->id]),
                        'class' => 'btn-primary',
                    ],
                    'visible' => app()->user->can(Permission::PERMISSION_WRITE, $project)
                ],

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
        'filterModel' => $workspaceSearch,
        'dataProvider' => $workspaceProvider,
        'columns' => [
            [
                'attribute' => 'id'
            ],
            [ 'label' => 'Title', 'attribute' => 'title', 'value' => 'title' ],
            [
                'label' => 'Cache status',
                'value' => function(Workspace $workspace) {
                    /** @var \prime\components\LimesurveyDataProvider $provider */
                    $provider = \Yii::$app->limesurveyDataProvider;
                    $timestamp = $provider->tokenCacheTime($workspace->project->base_survey_eid, $workspace->getAttribute('token'));
                    return isset($timestamp) ? \Carbon\Carbon::createFromTimestamp($timestamp)->diffForHumans() : null;
                }
            ],
            [
                'label' => '# Contributors',
                'value' => function(Workspace $project) {
                    return $project->getPermissions()->count();
                }
            ],
            [
                'label' => '# Health facilities',
                'attribute' => 'facilityCount',
            ],
            [
                'label' => '# Responses',
                'value' => 'responseCount'
            ],
            [
                'class' => \kartik\grid\DataColumn::class,
                'attribute' => 'closed',
                'format' => 'date',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'YYYY-MM-DD',
                        ],
                        'allowClear'=>true,
                    ],
                    'pluginEvents' => [
                        "apply.daterangepicker" => "function() { $('.grid-view').yiiGridView('applyFilter'); }"
                    ]
                ],
                'visible' => app()->controller->action->id === 'list-closed'
            ],
            'actions' => include('workspaces/actions.php')
        ]
    ]);
    ?>
</div>
