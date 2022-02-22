<?php

declare(strict_types=1);

use kartik\grid\GridView;
use prime\components\View;
use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\widgets\DrilldownColumn;
use prime\widgets\FavoriteColumn\FavoriteColumn;
use prime\widgets\Section;
use yii\bootstrap\ButtonGroup;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 */

$this->title = \Yii::t('app', 'Favorite workspaces');

Section::begin()
    ->withHeader($this->title);

echo GridView::widget([
    'caption' => ButtonGroup::widget([
        'options' => [
            'class' => 'pull-right',
        ],
    ]),
    'pjax' => true,
    'pjaxSettings' => [
        'options' => [
            // Just links in the header.
            'linkSelector' => 'th a'
        ]
    ],
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'project.title',
            'value' => function (WorkspaceForLimesurvey $workspace) {
                return Html::a($workspace->project->title, ['/project/workspaces', 'id' => $workspace->project->id]);
            },
            'format' => 'raw',
        ],
        [
            'class' => DrilldownColumn::class,
            'label' => 'Workspace',
            'link' => static function (WorkspaceForLimesurvey $workspace) {
                return ['workspace/limesurvey', 'id' => $workspace->id];
            },
            'permission' => Permission::PERMISSION_SURVEY_DATA,
            'attribute' => 'title',
        ],
        [
            'attribute' => 'latestUpdate',
        ],
        [
            'label' => '# Contributors',
            'attribute' => 'contributorCount'
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
            'class' => FavoriteColumn::class
        ],
    ]
]);

Section::end();
