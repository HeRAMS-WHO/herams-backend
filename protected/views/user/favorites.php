<?php

declare(strict_types=1);

use herams\common\models\PermissionOld;
use herams\common\models\Workspace;
use kartik\grid\GridView;
use prime\components\View;
use prime\widgets\DrilldownColumn;
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
            'linkSelector' => 'th a',
        ],
    ],
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'project.title',
            'value' => function (Workspace $workspace) {
                return Html::a($workspace->project->title, [
                    '/project/workspaces',
                    'id' => $workspace->project->id,
                ]);
            },
            'format' => 'raw',
        ],
        [
            'class' => DrilldownColumn::class,
            'label' => 'Workspace',
            'link' => static function (Workspace $workspace) {
                return [
                    'workspace/responses',
                    'id' => $workspace->id,
                ];
            },
            'permission' => PermissionOld::PERMISSION_SURVEY_DATA,
            'attribute' => 'title',
        ],
        [
            'attribute' => 'latestUpdate',
        ],
        [
            'label' => '# Contributors',
            'attribute' => 'contributorCount',
        ],
        [
            'label' => '# HSDUs',
            'attribute' => 'facilityCount',
        ],
        [
            'label' => '# Responses',
            'attribute' => 'responseCount',
        ],
    ],
]);

Section::end();
