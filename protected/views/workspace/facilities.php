<?php

declare(strict_types=1);

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\models\Permission;
use prime\helpers\Icon;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use SamIT\abac\interfaces\Resolver;
use yii\web\View;

/**
 * @var \prime\models\search\FacilitySearch $facilitySearch
 * @var int $closedCount
 * @var View $this
 * @var \prime\interfaces\WorkspaceForTabMenu $tabMenuModel
 * @var Resolver $abacResolver
 * @var iterable<VariableInterface> $variables
 */

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
            'label' => \Yii::t('app', 'Register new facility'),
            'link' => [
                'facility/create',
                'workspaceId' => $tabMenuModel->id(),
            ],
            'permission' => Permission::
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

            'headerName' => \Yii::t('app', 'Name'),
            'field' => 'name',
            'cellRenderer' => new \yii\web\JsExpression(<<<JS
                params => {
                    const a = document.createElement('a');
                    a.textContent = params.value;
                    a.href = '/facility/{id}/responses'.replace('{id}', params.data.id);
                    return a;
                }
            JS),
            //            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'Id'),
            'field' => 'id',
            'filter' => 'agNumberColumnFilter',
        ],
        ...\iter\map(fn (VariableInterface $variable) => [
            'field' => $variable->getName(),
            'headerName' => $variable->getTitle(\Yii::$app->language),
        ], $variables),
    ],

]);
Section::end();
