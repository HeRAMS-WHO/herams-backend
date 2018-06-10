<?php

use \app\components\Html;
use yii\bootstrap\Nav;
use prime\models\ar\Setting;

/**
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 * @var \prime\models\search\Project $projectSearch
 * @var int $closedCount
 * @var \yii\web\View $this
 * @var \prime\models\ar\Tool $tool
 *
 */

$this->params['sectionTitle'] = \Yii::t('app', 'Manage workspaces');
?>
<div class="col-xs-12">
    <?php


    echo \kartik\grid\GridView::widget([
        'caption' => !isset($caption) ? include('list/header.php') : $caption,
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                // Just links in the header.
                'linkSelector' => 'th a'
            ]
        ],
        'layout' => "{items}\n{pager}",
        'filterModel' => isset($projectSearch) ? $projectSearch : null,
        'dataProvider' => $projectsDataProvider,
        'columns' => [
            [
                'attribute' => 'id'
            ],
//            [
//                'label' => 'Project',
//                'attribute' => 'tool_id',
//                'value' => 'tool.acronym',
//                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
//                'filter' => $projectSearch->toolsOptions(),
//                'filterWidgetOptions' => [
//                    'pluginOptions' => [
//                        'allowClear' => true,
//                    ],
//
//                ],
//                'filterInputOptions' => [
//                    'placeholder' => \Yii::t('app', 'Select project')
//                ],
//                'visible' => !isset($hideToolColumn) || !$hideToolColumn
//            ],
            [ 'label' => 'Workspace', 'attribute' => 'title', 'value' => 'title' ],
            [
                'label' => '# responses',
                'value' => function(\prime\models\ar\Project $project) {
                    return \Yii::$app->cache->getOrSet('project.responses.' . $project->id, function() use ($project) {
                        return $project->getResponses()->size();
                    }, 3600);

                }
            ],
            [
                'label' => '# contributors',
                'value' => function(\prime\models\ar\Project $project) {
                    return $project->getPermissions()->count();
                    return $project->getResponses()->size();
                }
            ],
            [
                'attribute' => 'closed',
                'format' => 'date',
                'filterType' => \kartik\grid\GridView::FILTER_DATE_RANGE,
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
            'actions' => include('list/actions.php')
        ]
    ]);
    ?>
</div>
