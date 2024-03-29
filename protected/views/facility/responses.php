<?php

declare(strict_types=1);

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\models\PermissionOld;
use prime\components\View;
use prime\helpers\AgGridHelper;
use prime\helpers\Icon;
use prime\interfaces\FacilityForTabMenu;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\User;

/**
 * @var FacilityForTabMenu $facility
 * @var \herams\common\values\FacilityId $facilityId
 * @var ActiveDataProvider $responseProvider
 * @var View $this
 * @var array $updateSituationUrl
 * @var iterable<\Collecthor\DataInterfaces\VariableInterface> $variables
 */


$this->params['subject'] = $facility->getTitle();
//$this->title = \Yii::t('app', 'Situation Updates');
//$this->title = $tabMenuModel->getTitle();
$editIcon = preg_replace("/\r|\n/", "", Icon::edit());
$viewIcon = preg_replace("/\r|\n/", "", Icon::eye());
$deleteIcon = preg_replace("/\r|\n/", "", Icon::trash());
$this->beginBlock('tabs');

echo FacilityTabMenu::widget(
    [
        'facility' => $facility,
    ]
);
$this->endBlock();

Section::begin()
    ->withActions([
        [
            'label' => \Yii::t('app', 'Update Situation'),
            'icon' => Icon::add(),
            'link' => Url::to($updateSituationUrl),
            'permission' => function (FacilityForTabMenu $facility, User $userComponent) {
                return $facility->canReceiveSituationUpdate() && $facility->canCurrentUser(PermissionOld::PERMISSION_SURVEY_DATA);
            },
        ],
    ])
    ->withSubject($facility)
    ->withHeader(\Yii::t('app', 'Responses'));

echo \prime\widgets\AgGrid\AgGrid::widget([
    'route' => [
        '/api/facility/data-responses',
        'id' => $facilityId,
    ],
    'columns' => [
        [

            'headerName' => \Yii::t('app', 'ID'),
            'field' => 'id',
            'pinned' => 'left',
            'lockPinned' => true,
            'cellClass' => 'lock-pinned',
            'filter' => 'agNumberColumnFilter',
        ],
        ...\iter\map(fn (VariableInterface $variable) =>
        $variable->getName() === 'date_of_update' ?
            AgGridHelper::generateColumnTypeDate($variable->getTitle(\Yii::$app->language), $variable->getName()) :
            [
                'field' => $variable->getName(),
                'headerName' => $variable->getTitle(\Yii::$app->language),
                'filter' => 'agTextColumnFilter',
            ], $variables),
        // [

        //     'headerName' => \Yii::t('app', 'Name'),
        //     'field' => 'name',
        // ],
        AgGridHelper::generateColumnTypeDate(
            'Date of update',
            'date_of_update',
            'desc',
            'left'
        ),
        // [

        //     'headerName' => \Yii::t('app', 'Response Type'),
        //     'field' => 'response_type',
        //     'filter' => 'agNumberColumnFilter',
        // ],
        [

            'headerName' => \Yii::t('app', 'Status'),
            'field' => 'status',
            'filter' => 'agTextColumnFilter',
        ],
        // [

        //     'headerName' => \Yii::t('app', 'Created Date'),
        //     'field' => 'created_at', q
        //     'filter' => 'agNumberColumnFilter',
        // ],
        // [

        //     'headerName' => \Yii::t('app', 'Created By'),
        //     'field' => 'created_by',
        //     'filter' => 'agNumberColumnFilter',
        // ],
        [

            'headerName' => \Yii::t('app', 'Last modified on'),
            'field' => 'last_modified_date',
            'filter' => 'agTextColumnFilter',
        ],

        [

            'headerName' => \Yii::t('app', 'Last modified by'),
            'field' => 'last_modified_by',
            'filter' => 'agTextColumnFilter',
        ],

        [
            'headerName' => \Yii::t('app', 'Action'),
            'pinned' => 'right',
            'lockPinned' => true,
            'cellClass' => 'lock-pinned',
            'cellRenderer' => new \yii\web\JsExpression(<<<JS
                params => {
                    if (params.data == null) {
                        const a = document.createElement('span');
                        a.textContent = params.value;
                        return a; 
                    }
                    
                    let box = document.createElement("div");

                    svgEditIcon = '$editIcon';
                    const e = document.createElement('a');
                   // e.textContent = Icon::edit() ;
                    //e.href = '/project/{id}/workspaces'.replace('{id}', params.data.id);
                    e.href = '/facility/'+params.data.facilityId+'/edit-situation/' + params.data.id;
                    e.setAttribute('class','ag-grid-action-icon');
                    e.innerHTML += (svgEditIcon);

                    svgViewIcon = '$viewIcon';

                    const v = document.createElement("a");
                    v.href = '/facility/'+params.data.facilityId+'/view-situation/' + params.data.id;
                    v.setAttribute("class","ag-grid-action-icon");
                    v.innerHTML += (svgViewIcon);

                    svgDeleteIcon = '$deleteIcon';

                    const d = document.createElement("a");
                    d.href = '/facility/'+params.data.facilityId+'/delete-situation/' + params.data.id;
                    d.setAttribute("class","ag-grid-action-icon");
                    d.setAttribute("data-confirm","Are you sure you wish to remove this response from the system?");
                    d.innerHTML += (svgDeleteIcon);

                    box.appendChild(e);
                    box.appendChild(v);
                    box.appendChild(d);
                    return  box 
                    return e;
                    
                }
            JS),
            'field' => 'action',
            'filter' => false,
        ],

    ],

]);

Section::end();
