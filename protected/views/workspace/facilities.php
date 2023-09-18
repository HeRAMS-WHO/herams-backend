<?php

declare(strict_types=1);

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\models\PermissionOld;
use prime\helpers\AgGridHelper;
use prime\helpers\Icon;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use SamIT\abac\interfaces\Resolver;
use yii\web\View;

/**
 * @var int $closedCount
 * @var View $this
 * @var \prime\interfaces\WorkspaceForTabMenu $tabMenuModel
 * @var Resolver $abacResolver
 * @var iterable<VariableInterface> $variables
 */
echo "<script> const updateSituationContent = `" . Icon::add() . \Yii::t('app', 'Update Situation') . "`</script>";
echo "<script> const closedFacility = `" . Icon::close() . \Yii::t('app', 'No updates expected') . "`</script>";

$this->title = $tabMenuModel->title();

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget(
    [
        'workspace' => $tabMenuModel,
    ]
);
$this->endBlock();

Section::begin([
    'actions' => [
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Register new HSDU'),
            'link' => [
                'facility/create',
                'workspaceId' => $tabMenuModel->id(),
            ],
            'permission' => PermissionOld::
PERMISSION_CREATE_FACILITY,
        ],
    ],
])->withSubject($tabMenuModel);

echo \prime\widgets\AgGrid\AgGrid::widget([
    'route' => [
        'api/workspace/facilities',
        'id' => $tabMenuModel->id(),
    ],
    'columns' => [
        [

            'headerName' => \Yii::t('app', 'ID'),
            'field' => 'id',
            'pinned' => 'left',
            'lockPinned' => true,
            'cellClass' => 'lock-pinned',
            //'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'HSDU name'),
            'field' => 'name',
            'cellRenderer' => new \yii\web\JsExpression(<<<JS
                params => {
                    const a = document.createElement('a');
                    a.textContent = params.value;
                    a.href = '/facility/{id}/responses'.replace('{id}', params.data.id);
                    a.setAttribute('class','agGridAnkur');
                    return a;
                }
            JS),
            //            'filter' => 'agNumberColumnFilter',
            'pinned' => 'left',
            'lockPinned' => true,
            'cellClass' => 'lock-pinned',
            'minWidth' => '200',
        ],
        AgGridHelper::generateColumnTypeDate(
            'Date of update',
            'LAST_DATE_OF_UPDATE',
            'desc',
            'left'
        ),
        ...$tableCols,
        [
            'headerName' => '',
            'field' => 'actions',
            'cellRenderer' => new \yii\web\JsExpression(<<<JS
                params => {
                    const span = document.createElement('span');
                    span.className='d-block';
                    const a = document.createElement('a');
                    a.innerHTML = updateSituationContent;
                    a.href = '/facility/{id}/update-situation'.replace('{id}', params.data.id);
                    let className = 'btn btn-default';
                    if (params.data.can_receive_situation_update == 0){
                        a.onclick = () => false;
                        className = 'btn';
                        a.innerHTML = closedFacility;
                    }
                    a.setAttribute('class', className);
                    span.appendChild(a)
                    
                    return span;
                }
            JS),
            'minWidth' => '200',
            'filter' => null,
            'pinned' => 'right',
            'lockPinned' => true,
            'cellClass' => 'lock-pinned',


        ],
    ],

]);
Section::end();
