<?php
declare(strict_types=1);

use kartik\grid\GridView;
use yii\bootstrap\ButtonGroup;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = \Yii::t('app', 'Favorite workspaces');
?>
<div class="form-content form-bg full-width">
<?php
$this->params['breadcrumbs'][] = ['label' => ""];
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
            'attribute' => 'title'
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
        'actions' => require(__DIR__ . '/../project/workspaces/actions.php')
    ]
]);
?>
</div>
