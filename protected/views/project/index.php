<?php

declare(strict_types=1);

use herams\common\models\Permission;
use prime\helpers\Icon;
use prime\models\search\Project as SearchModelProject;
use prime\widgets\AgGrid\AgGrid;
use prime\widgets\Section;
use SamIT\abac\interfaces\Resolver;
use yii\data\ActiveDataProvider;
use yii\web\User;
use yii\web\View;

/**
 * @var View $this
 */

$this->title = \Yii::t('app', 'Projects');

Section::begin([
    'actions' => [
        [
            'label' => \Yii::t('app', 'Create project'),
            'link' => ['project/create'],
            'style' => 'primary',
            'icon' => Icon::add(),
            'permission' => Permission::PERMISSION_CREATE_PROJECT,
        ],
    ],
])->withHeader($this->title);
$icon = json_encode(Icon::eye());
echo AgGrid::widget([
    'route' => ['api/project/index'],
    'columns' => [
        [
            'headerName' => \Yii::t('app', 'Name'),
            'cellRenderer' => new \yii\web\JsExpression(<<<JS
                params => {
                    if (params.data == null) {
                        const a = document.createElement('span');
                        a.textContent = params.value;
                        return a; 
                    }
                    const a = document.createElement('a');
                    a.textContent = params.value;
                    a.href = '/project/{id}/workspaces'.replace('{id}', params.data.id);
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

            'headerName' => \Yii::t('app', '# Workspaces'),
            'field' => 'workspaceCount',
            'filter' => 'agNumberColumnFilter',
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
            'headerName' => \Yii::t('app', 'Project coordinator'),
            'field' => 'coordinator',
            'filter' => 'agTextColumnFilter',
        ],
    ],

]);
Section::end();
