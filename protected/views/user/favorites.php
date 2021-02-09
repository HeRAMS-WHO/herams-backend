<?php
declare(strict_types=1);

use kartik\grid\GridView;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;
use prime\models\ar\Permission;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \prime\components\View $this
 */

$this->title = \Yii::t('app', 'Favorite workspaces');
echo Html::beginTag('div', ['class' => 'content']);
?>
<div class="form-content form-bg full-width">
<?php

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
            'value' => function (\prime\models\ar\Workspace $workspace) {
                return \yii\helpers\Html::a($workspace->project->title, ['/project/workspaces', 'id' => $workspace->project->id]);
            },
            'format' => 'raw',
        ],
        [
            'class' => \prime\widgets\DrilldownColumn::class,
            'label' => 'Workspace',
            'link' => static function ($project) {
                return ['project/workspaces', 'id' => $project->id];
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
            'class' => \prime\widgets\FavoriteColumn\FavoriteColumn::class
        ],
    ]
]);
?>
</div>

<?php
echo Html::endTag('div');