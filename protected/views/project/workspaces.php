<?php

declare(strict_types=1);

use herams\common\models\Permission;
use herams\common\models\Project;
use prime\helpers\Icon;
use prime\widgets\AgGrid\AgGrid;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\web\JsExpression;
use yii\web\View;

/**
 * @var int $closedCount
 * @var View $this
 * @var Project $project
 * @var \herams\common\values\ProjectId $projectId
 * @var array $dataRoute
 */


$this->params['subject'] = $project->getTitle();
$this->title = \Yii::t('app', 'Workspaces');
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
        ],
    ]
);

echo AgGrid::widget([
    'route' => $dataRoute,
    'columns' => [
//        [
//
//            'headerName' => \Yii::t('app', 'Favorite'),
//            'field' => 'favorite_id',
//            'filter' => new JsExpression('ToggleButtonFilter'),
//            'cellRenderer' => new JsExpression('ToggleButtonRenderer'),
//            'cellRendererParams' => [
//                'endpoint' => \yii\helpers\Url::to([
//                    '/api/user/workspaces',
//                    'id' => \Yii::$app->user->id,
//                ], true),
//                //                'idField' => 'id'
//            ],
//            //            'width'=> 100,
//            //            'suppressSizeToFit' => true,
//            'comparator' => new JsExpression(
//                '(a, b) => a == b ? 0 : a ? 1: -1'
//            ),
//        ],
        [

            'headerName' => \Yii::t('app', 'Name'),
            'cellRenderer' => new JsExpression(<<<JS
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


Section::end();
