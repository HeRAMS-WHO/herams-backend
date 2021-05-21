<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\search\Workspace;
use prime\widgets\DateTimeColumn;
use prime\widgets\DrilldownColumn;
use prime\widgets\FavoriteColumn\FavoriteColumn;
use prime\widgets\IdColumn;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use SamIT\abac\interfaces\Resolver;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @var ActiveDataProvider $facilityProvider
 * @var \prime\models\search\FacilitySearch $facilitySearch
 * @var int $closedCount
 * @var View $this
 * @var \prime\models\ar\read\Workspace $workspace
 * @var Resolver $abacResolver
 */

$this->title = $workspace->title;
$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget(
    ['workspace' => $workspace]
);
$this->endBlock();

Section::begin();
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
                'class' => IdColumn::class,
            ],
            [
                'attribute'  => 'name',
            ],
            [
                'attribute'  => 'alternative_name',
            ],
            [
                'attribute'  => 'code',
            ],
            [
                'attribute'  => 'coords',
                'format' => \prime\components\Formatter::FORMAT_COORDS
            ],
        ],
    ]
);

Section::end();
