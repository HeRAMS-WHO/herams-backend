<?php

declare(strict_types=1);

use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\search\Workspace as WorkspaceSearch;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use SamIT\abac\interfaces\Resolver;
use yii\data\ActiveDataProvider;
use yii\web\User as UserComponent;
use yii\web\View;

/**
 * @var ActiveDataProvider $workspaceProvider
 * @var WorkspaceSearch $workspaceSearch
 * @var int $closedCount
 * @var View $this
 * @var Project $project
 * @var Resolver $abacResolver
 * @var \prime\values\ProjectId $projectId
 * @var UserComponent $userComponent
 */

$this->title = \Yii::t('app', 'Workspaces in {project}', [
    'project' => $project->title,
]);
$this->beginBlock('tabs');
echo ProjectTabMenu::widget(
    [
        'project' => $project,
    ]
);
$this->endBlock();

Section::begin(
    [
        'subject' => $project,
        'actions' => [
            [
                'icon' => Icon::add(),
                'label' => \Yii::t('app', 'Create workspace'),
                'link' => [
                    'workspace/create',
                    'project_id' => $project->id,
                ],
                'permission' => Permission::PERMISSION_MANAGE_WORKSPACES,
            ],
            [
                'icon' => Icon::download_1(),
                'label' => \Yii::t('app', 'Import workspaces'),
                'link' => [
                    'workspace/import',
                    'project_id' => $project->id,
                ],
                'permission' => Permission::PERMISSION_MANAGE_WORKSPACES,
            ],
        ],
    ]
);

echo \prime\widgets\AgGrid\AgGrid::widget([
    'route' => [
        'api/project/workspaces',
        'id' => $projectId
    ],
    'columns' => [
        [

            'headerName' => \Yii::t('app', 'Favorite'),
            'field' => 'id',
            'filter' => 'agNumberColumnFilter',
            'cellRenderer' => new \yii\web\JsExpression('ToggleButtonRenderer'),
            'cellRendererParams' => [
                'endpoint' => \yii\helpers\Url::to(['/api/user/workspaces', 'id' => \Yii::$app->user->id], true),
                'clicked' => new \yii\web\JsExpression('function(field) {
                    alert(`${field} was clicked`);
                }')
            ]
        ],
        [

            'headerName' => \Yii::t('app', 'Name'),
            'cellRenderer' => new \yii\web\JsExpression(<<<JS
                params => {
                    const a = document.createElement('a');
                    a.textContent = params.value;
                    a.href = '/workspace/{id}/facilities'.replace('{id}', params.data.id);
                    return a;
                }
            JS),
            'field' => 'name',
            //            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'Id'),
            'field' => 'id',
            'filter' => 'agNumberColumnFilter',
        ],

        [

            'headerName' => \Yii::t('app', 'Latest update'),
            'field' => 'latestUpdate',
            'filter' => 'agDateColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', '# Contributors'),
            'field' => 'contributorCount',
            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', '# Health facilities'),
            'field' => 'facilityCount',
            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', '# Responses'),
            'field' => 'responseCount',
            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'Workspace owner'),
            'field' => 'leadNames',
            'filter' => 'agTextColumnFilter',
        ],

    ],

]);

//echo GridView::widget(
//    [
//        'pjax' => true,
//        'export' => false,
//        'pjaxSettings' => [
//            'options' => [
//                // Just links in the header.
//                'linkSelector' => 'th a',
//            ],
//        ],
//        'filterModel' => $workspaceSearch,
//        'dataProvider' => $workspaceProvider,
//        'columns' => [
//            [
//                'class' => FavoriteColumn::class,
//
//                'filter' => [
//                    "1" => \Yii::t('app', 'Favorites only'),
//                    "0" => \Yii::t('app', 'Non-favorites only'),
//                ],
//            ],
//            [
//                'class' => IdColumn::class,
//            ],
//            [
//                'class' => DrilldownColumn::class,
//                'attribute' => 'title',
//                'permission' => Permission::PERMISSION_LIST_FACILITIES,
//                'link' => static fn (Workspace $workspace) => [
//                    'workspace/facilities',
//                    'id' => $workspace->id,
//                ],
//            ],
//            [
//                'attribute' => 'latestUpdate',
//                'class' => DateTimeColumn::class,
//            ],
//            [
//                'attribute' => 'contributorCount',
//            ],
//            [
//                'attribute' => 'facilityCount',
//            ],
//            [
//                'attribute' => 'responseCount',
//            ],
//            [
//                'label' => \Yii::t('app', 'Workspace owner'),
//                'value' => static function (Workspace $workspace) use ($userComponent) {
//                    return implode(
//                        '<br>',
//                        ArrayHelper::merge(
//                            toArray(map(index('name'), $workspace->getLeads())),
//                            array_filter([! $userComponent->can(Permission::PERMISSION_ADMIN, $workspace) ? Html::tag('i', Html::a(\Yii::t('app', 'Request access'), [
//                                'workspace/request-access',
//                                'id' => $workspace->id,
//                            ])) : null])
//                        )
//                    );
//                },
//                'format' =>
//'html',
//            ],
//        ],
//    ]
//);

Section::end();
