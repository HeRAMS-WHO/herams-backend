<?php

use \app\components\Html;
use prime\models\ar\Setting;

/**
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 * @var \prime\models\search\Project $projectSearch
 */

$this->title = \Yii::t('app', 'Manage workspaces');
//$this->params['breadcrumbs'][] = [
//    'label' => \Yii::t('app', 'Project overview'),
//    'url' => ['tool/overview', 'id' => $tool->id]
//]

?>
<div class="col-xs-12">
    <?php

  echo \kartik\grid\GridView::widget([
        'caption' => $this->render('list/header', ['tool' => $tool]),
        'layout' => "{items}\n{pager}",
        'filterModel' => $projectSearch,
        'dataProvider' => $projectsDataProvider,
        'columns' => [
            [
                'attribute' => 'id'
            ],
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
            [
                'attribute' => 'created',
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
            ],
            
            'actions' => include('list/actions.php')
        ]
    ]);
    ?>
</div>
