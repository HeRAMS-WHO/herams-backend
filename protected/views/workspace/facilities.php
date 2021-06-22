<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\models\facility\FacilityForList;
use prime\widgets\DrilldownColumn;
use prime\widgets\IdColumn;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use prime\widgets\UuidColumn;
use SamIT\abac\interfaces\Resolver;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var ActiveDataProvider $facilityProvider
 * @var \prime\models\search\FacilitySearch $facilitySearch
 * @var int $closedCount
 * @var View $this
 * @var \prime\interfaces\WorkspaceForTabMenu $tabMenuModel
 * @var Resolver $abacResolver
 */

$this->params['breadcrumbs'][] = [
    'label' => $tabMenuModel->projectTitle(),
    'url' => ['project/workspaces', 'id' => $tabMenuModel->projectId()]
];

$this->title = $tabMenuModel->title();
$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget(
    ['workspace' => $tabMenuModel]
);
$this->endBlock();

Section::begin([
    'actions' => [
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Register new facility'),
            'link' => ['facility/create', 'parent_id' => $tabMenuModel->id()],
            'permission' => Permission::PERMISSION_SURVEY_DATA
        ],
    ]
]);
echo GridView::widget(
    [
        'pjax'         => true,
        'export'       => false,
        'pjaxSettings' => [
            'options' => [
                // Just links in the header.
                'linkSelector' => 'th a',
            ],
        ],
        'filterModel'  => $facilitySearch,
        'dataProvider' => $facilityProvider,
        'columns'      => [
            [
                'class'      => DrilldownColumn::class,
                'attribute'  => FacilityForList::NAME,
                'permission' => Permission::PERMISSION_LIST_FACILITIES,
                'link'       => static fn(FacilityForList $facility) => ['facility/responses', 'id' => (string) $facility->getId()]
            ],
            [
                'class' => IdColumn::class
            ],
            [
                'attribute'  => FacilityForList::ALTERNATIVE_NAME,
            ],
            [
                'attribute'  => FacilityForList::CODE,
            ],
            [
                'attribute' => FacilityForList::RESPONSE_COUNT
            ],
            [
                'class' => UuidColumn::class
            ],

        ],
    ]
);

Section::end();