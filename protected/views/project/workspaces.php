<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\models\search\Workspace as WorkspaceSearch;
use prime\widgets\DateTimeColumn;
use prime\widgets\DrilldownColumn;
use prime\widgets\FavoriteColumn\FavoriteColumn;
use prime\widgets\IdColumn;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use SamIT\abac\interfaces\Resolver;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\User as UserComponent;
use yii\web\View;
use function iter\func\index;
use function iter\rewindable\map;
use function iter\toArray;

/**
 * @var ActiveDataProvider $workspaceProvider
 * @var WorkspaceSearch $workspaceSearch
 * @var int $closedCount
 * @var View $this
 * @var Project $project
 * @var Resolver $abacResolver
 * @var UserComponent $userComponent
 */

$this->title = $project->title;
$this->beginBlock('tabs');
echo ProjectTabMenu::widget(
    ['project' => $project]
);
$this->endBlock();

Section::begin(
    [
        'subject' => $project,
        'actions' => [
            [
                'icon'       => Icon::add(),
                'label'      => \Yii::t('app', 'Create workspace'),
                'link'       => [
                    'workspace/create', 'project_id' => $project->id
                ],
                'permission' => Permission::PERMISSION_MANAGE_WORKSPACES,
            ],
            [
                'icon'       => Icon::download_1(),
                'label'      => \Yii::t('app', 'Import workspaces'),
                'link'       => [
                    'workspace/import', 'project_id' => $project->id
                ],
                'permission' => Permission::PERMISSION_MANAGE_WORKSPACES,
            ],
        ],
    ]
);
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
        'filterModel'  => $workspaceSearch,
        'dataProvider' => $workspaceProvider,
        'columns'      => [
            [
                'class'  => FavoriteColumn::class,

                'filter' => [
                    "1" => \Yii::t('app', 'Favorites only'),
                    "0" => \Yii::t('app', 'Non-favorites only'),
                ],
            ],
            [
                'class' => IdColumn::class,
            ],
            [
                'class'      => DrilldownColumn::class,
                'attribute'  => 'title',
                'permission' => Permission::PERMISSION_LIST_FACILITIES,
                'link'       => static fn ($workspace) => [
                    'workspace/facilities', 'id' => $workspace->id
                ],
            ],
            [
                'attribute' => 'latestUpdate',
                'class'     => DateTimeColumn::class,
            ],
            ['attribute' => 'contributorCount'],
            ['attribute' => 'facilityCount'],
            ['attribute' => 'responseCount'],
            [
                'label' => \Yii::t('app', 'Workspace owner'),
                'value' => static function (Workspace $workspace) use ($userComponent) {
                    return implode(
                        '<br>',
                        ArrayHelper::merge(
                            toArray(map(index('name'), $workspace->getLeads())),
                            array_filter([!$userComponent->can(Permission::PERMISSION_ADMIN, $workspace) ? Html::tag('i', Html::a(\Yii::t('app', 'Request access'), ['workspace/request-access', 'id' => $workspace->id])) : null])
                        )
                    );
                },
                'format' => 'html',
            ]
        ],
    ]
);

Section::end();
