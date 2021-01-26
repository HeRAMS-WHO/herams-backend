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
            'label' => 'Workspace',
            'attribute' => 'title',
            'content' => function ($workspace) {
                return (\Yii::$app->user->can(Permission::PERMISSION_SURVEY_DATA, $workspace)) ?
                    Html::a(
                        $workspace->title,
                        ['workspace/limesurvey', 'id' => $workspace->id],
                        [
                            'title' => $workspace->title,
                        ]
                    ) : $workspace->title;
            }
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