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
$this->registerCSSFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
$this->registerJSFile('https://cdn.jsdelivr.net/npm/flatpickr');
$this->registerJsFile('https://momentjs.com/downloads/moment-with-locales.js');
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
        // [

        //     'headerName' => \Yii::t('app', 'ID'),
        //     'field' => 'id',
        //     //'filter' => 'agNumberColumnFilter',
        // ],
        [

            'headerName' => \Yii::t('app', 'Title'),
            'cellRenderer' => new JsExpression(<<<JS
                params => {
                    const a = document.createElement('a');
                    a.textContent = params.value;
                    a.href = '/workspace/{id}/facilities'.replace('{id}', params.data.id);
                    a.setAttribute('class','agGridAnkur');
                    return a;
                }
            JS),
            'field' => 'name',
            //            'filter' => 'agNumberColumnFilter',
        ],

        [

            'headerName' => \Yii::t('app', 'ID'),
            'field' => 'id',
            'filter' => 'agNumberColumnFilter',
        ],
        [
            'headerName' => \Yii::t('app', 'Date of update'),
            'field' => 'date_of_update',
            'filter' => 'agDateColumnFilter',
            'filterParams' => new \yii\web\JsExpression(<<<JS
                {
                    comparator: function(filterLocalDateAtMidnight, cellValue) {
                        var dateParts = cellValue.indexOf('-') > -1 ? cellValue.split("-") : cellValue.split("/");
                        var isISO = dateParts[0].length === 4;
                        var cellYear = isISO ? Number(dateParts[0]) : Number(dateParts[2]);
                        var cellMonth = isISO ? Number(dateParts[1]) - 1 : Number(dateParts[0]) - 1;
                        var cellDay = isISO ? Number(dateParts[2]) : Number(dateParts[1]);
                        var cellDate = new Date(cellYear, cellMonth, cellDay).setHours(0, 0, 0, 0);
        
                        var filterYear = filterLocalDateAtMidnight.getFullYear();
                        var filterMonth = filterLocalDateAtMidnight.getMonth();
                        var filterDay = filterLocalDateAtMidnight.getDate();
                        var filterDate = new Date(filterYear, filterMonth, filterDay).setHours(0, 0, 0, 0);
        
                        if (cellDate === filterDate) {
                            return 0;
                        }
                        if (cellDate < filterDate) {
                            return -1;
                        }
                        return 1;
                    }
                }
            JS),
            'cellRenderer' => new \yii\web\JsExpression(<<<JS
                function(params) {
                    if (params.value) {
                        var dateFormat = 'MM/DD/YYYY'; // Replace this with the actual format you are using
                        var dateParts = params.value.split("-");
                        var dateObject = new Date(Number(dateParts[0]), Number(dateParts[1]) - 1, Number(dateParts[2]));
                        
                        if (dateFormat === 'MM/DD/YYYY') {
                            return (dateObject.getMonth() + 1) + '/' + dateObject.getDate() + '/' + dateObject.getFullYear();
                        } else if (dateFormat === 'DD/MM/YYYY') {
                            return dateObject.getDate() + '/' + (dateObject.getMonth() + 1) + '/' + dateObject.getFullYear();
                        } else {
                            // Default to ISO format
                            return params.value;
                        }
                    }
                    return '';
                }
            JS)
        ],
        [

            'headerName' => \Yii::t('app', '# Contributors'),
            'field' => 'contributorCount',
            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', '# HSDUs'),
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
