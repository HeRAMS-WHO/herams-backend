<?php

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\interfaces\WorkspaceForTabMenu;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\search\Response;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $responseProvider
 * @var Response $responseSearch
 * @var int $closedCount
 * @var View $this
 * @var WorkspaceForLimesurvey $workspace
 * @var WorkspaceForTabMenu $tabMenuModel
 */

$this->title = $workspace->title;

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
            'attribute' => 'updated_at'
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
                'compare' => function ($url, \prime\models\ar\ResponseForLimesurvey $model, $key) {
                    $url = ['response/compare', 'id' => $model->id, 'survey_id' => $model->survey_id];
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
