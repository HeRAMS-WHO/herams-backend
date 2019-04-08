<?php

/**
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 * @var \prime\models\search\Workspace $projectSearch
 * @var int $closedCount
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $project
 *
 */

use kartik\grid\GridView;
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
    'url' => app()->user->can(\prime\models\permissions\Permission::PERMISSION_WRITE, $project) ? ['project/update', 'id' => $project->id] : null
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
                    'visible' => app()->user->can(\prime\models\permissions\Permission::PERMISSION_INSTANTIATE, $project)
                ],
                [
                    'label' => \Yii::t('app', 'Create workspace'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['workspace/create', 'project_id' => $project->id]),
                        'class' => 'btn-primary',
                    ],
                    'visible' => app()->user->can(\prime\models\permissions\Permission::PERMISSION_INSTANTIATE, $project)
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
                'label' => '# responses',
                'value' => function(\prime\models\ar\Workspace $workspace) {
                    return \Yii::$app->cache->getOrSet('project.responses.' . $workspace->id, function() use ($workspace) {
                        return count($workspace->getResponses());
                    }, 3600);

                }
            ],
            [
                'label' => '# contributors',
                'value' => function(\prime\models\ar\Workspace $project) {
                    return $project->getPermissions()->count();
                }
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
