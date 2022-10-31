<?php

declare(strict_types=1);

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\widgets\AgGrid\AgGrid;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 * @var Project $project
 * @var array $dataRoute
 */

$this->params['subject'] = $project->getTitle();
$this->title = \Yii::t('app', "Dashboard settings");

$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();

Section::begin([
    'actions' => [
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Create page'),
            'link' => [
                'page/create',
                'project_id' => $project->id,
            ],
            'style' =>
'primary',
        ],
        [
            'icon' => Icon::download_1(),
            'label' => \Yii::t('app', 'Import pages'),
            'link' => [
                'project/import-dashboard',
                'id' => $project->id,
            ],
        ],
        [
            'icon' => Icon::export(),
            'label' => \Yii::t('app', 'Export all'),
            'link' => [
                'project/export-dashboard',
                'id' => $project->id,
            ],
        ],
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Project dashboard'),
            'link' => [
                'project/view',
                'id' => $project->id,
            ],
            'permission' => Permission::PERMISSION_READ,
            'visible' => $project->
pageCount >
0,
        ],
    ],
])->withHeader(\Yii::t('app', 'pages'));

echo AgGrid::widget([
    'route' => $dataRoute,
    'columns' => [
        [

            'headerName' => \Yii::t('app', 'Title'),
            'cellRenderer' => new JsExpression(<<<JS
                params => {
                    const a = document.createElement('a');
                    a.textContent = params.value;
                    a.href = '/page/{id}/update'.replace('{id}', params.data.id);
                    return a;
                }
            JS),
            'field' => 'title',
            //            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'Id'),
            'field' => 'id',
            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'Parent Id'),
            'field' => 'parent_id',
            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'Sort'),
            'field' => 'sort',
            'filter' => 'agNumberColumnFilter',
        ]
    ],

]);
Section::end();
