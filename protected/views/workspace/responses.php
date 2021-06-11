<?php

/**
 * @var \yii\data\ActiveDataProvider $responseProvider
 * @var \prime\models\search\Response $responseSearch
 * @var int $closedCount
 * @var \yii\web\View $this
 * @var \prime\models\ar\Workspace $workspace
 * @var \prime\interfaces\WorkspaceForTabMenu $tabMenuModel
 */

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = [
    'label' => $workspace->project->title,
    'url' => ['project/workspaces', 'id' => $workspace->project->id]
];
$this->title = \Yii::t('app', "Workspace {workspace}", [
    'workspace' => $workspace->title,
]);

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $tabMenuModel,
]);
$this->endBlock();

Section::begin([
    'header' => \Yii::t('app', 'Responses')
]);
echo GridView::widget([
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
Section::end();
