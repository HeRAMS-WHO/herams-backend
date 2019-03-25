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

$this->title = \Yii::t('app', 'Manage workspaces in {project}', ['project' => $project->getDisplayField()]);

$this->params['breadcrumbs'][] = [
    'label' => $project->getDisplayField(),
    'url' => ['project/view', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Manage workspaces'),
//    'url' => ['project/view', 'id' => $project->id]
];


?>
<div class="col-xs-12">
    <?php


    echo GridView::widget([
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
                'value' => function(\prime\models\ar\Workspace $project) {
                    return \Yii::$app->cache->getOrSet('project.responses.' . $project->id, function() use ($project) {
                        return $project->getResponses()->size();
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
