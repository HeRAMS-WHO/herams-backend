<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\models\facility\FacilityForList;
use prime\widgets\DrilldownColumn;
use prime\widgets\IdColumn;
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
 * @var \prime\interfaces\WorkspaceForNewOrUpdateFacility $workspace
 * @var Resolver $abacResolver
 */

$this->params['breadcrumbs'][] = [
    'label' => $workspace->projectTitle(),
    'url' => ['project/workspaces', 'id' => $workspace->projectId()]
];

$this->title = $workspace->title();
$this->beginBlock('tabs');
//echo WorkspaceTabMenu::widget(
//    ['workspace' => $workspace]
//);
$this->endBlock();

Section::begin([
    'actions' => [
        [
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Register new facility'),
            'link' => ['facility/create', 'parent_id' => $workspace->id()],
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
                'class' => UuidColumn::class
            ],
            [
                'class' => IdColumn::class
            ],
            [
                'class'      => DrilldownColumn::class,
                'attribute'  => FacilityForList::NAME,
                'permission' => Permission::PERMISSION_LIST_FACILITIES,
                'link'       => static fn(FacilityForList $facility) => ['facility/responses', 'id' => (string) $facility->getId()]
            ],
            [
                'attribute'  => FacilityForList::ALTERNATIVE_NAME,
            ],
            [
                'attribute'  => FacilityForList::CODE,
            ],
            [
                'attribute' => FacilityForList::RESPONSE_COUNT
            ]
        ],
    ]
);

Section::end();
