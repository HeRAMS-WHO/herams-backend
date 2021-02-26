<?php
declare(strict_types=1);

use app\components\Form;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use prime\components\View;
use prime\helpers\Icon;
use prime\models\ar\Element;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\widgets\Section;
use yii\bootstrap\ButtonGroup;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use function iter\chain;
use function iter\toArrayWithKeys;

/**
 * @var Page $page
 * @var View $this
 */

$this->params['breadcrumbs'][] = [
    'label' => $page->project->title,
    'url' => ['project/update', 'id' => $page->project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Dashboard settings'),
    'url' => ['project/pages', 'id' => $page->project->id]
];

$this->title = $page->title;

Section::begin()
    ->withHeader(\Yii::t('app', 'Update Page'));

$form = ActiveForm::begin([
    'id' => 'update-page',
    'method' => 'PUT',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => true,
        'defaultPlaceholder' => false,
        'labelSpan' => 3
    ]
]);

echo Form::widget([
    'form' => $form,
    'model' => $page,
    'columns' => 1,
    "attributes" => [
        'title' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\typeahead\Typeahead::class,
            'hint' => \Yii::t('app', 'If you use a predefined option it will automatically be translated'),
            'options' => [
                'useHandleBars' => false,
                'defaultSuggestions' => $page->titleOptions(),
                'dataset' => [
                    [
                        'local' => $page->titleOptions(),
                        'sufficient' => 0
                    ]

                ],
            ]


        ],
        'parent_id' => [
            'attribute' => 'parent_id',
            'type' => Form::INPUT_DROPDOWN_LIST,

            'items' => toArrayWithKeys(chain(['' => 'No parent'], $page->parentOptions()))
        ],
        'add_services' => [
            'type' => Form::INPUT_CHECKBOX
        ],
        'sort' => [
            'type' => Form::INPUT_TEXT,
        ],
        [
            'type' => Form::INPUT_RAW,
            'value' => ButtonGroup::widget([
                'options' => [
                    'class' => 'pull-right'
                ],
                'buttons' => [
                    ['label' => \Yii::t('app', 'Update page'), 'options' => ['class' => ['btn', 'btn-primary']]],
                    Html::a(\Yii::t('app', 'Back to list'), ['project/pages', 'id' => $page->project_id], ['class' => ['btn', 'btn-default']])
                ]
            ])
        ]
    ]
]);
ActiveForm::end();

Section::end();

Section::begin()
->withHeader(\Yii::t('app', 'Page elements'));

echo GridView::widget([
    'caption' => ButtonGroup::widget([
        'options' => [
            'class' => 'pull-right',
            'style' => [
                'margin-bottom' => '10px'
            ]
        ],
        'buttons' => [
            [
                'label' => \Yii::t('app', 'Create table'),
                'tagName' => 'a',
                'options' => [
                    'href' => Url::to(['element/create', 'page_id' => $page->id, 'type' => 'table']),
                    'class' => 'btn-default',
                ],
            ],
            [
                'label' => \Yii::t('app', 'Create map'),
                'tagName' => 'a',
                'options' => [
                    'href' => Url::to(['element/create', 'page_id' => $page->id, 'type' => 'map']),
                    'class' => 'btn-default',
                ],
            ],
            [
                'label' => \Yii::t('app', 'Create chart'),
                'tagName' => 'a',
                'options' => [
                    'href' => Url::to(['element/create', 'page_id' => $page->id, 'type' => 'chart']),
                    'class' => 'btn-default',
                ],
            ],
        ]
    ]),
    'dataProvider' => new ActiveDataProvider(['query' => $page->getElements()]),
    'columns' => [
        'id',
        'title',
        'code',
        'type',
        'sort',
        'actions' => [
            'class' => \kartik\grid\ActionColumn::class,
            'controller' => 'element',
            'width' => '100px',
            'template' => '{update} {remove}',
            'buttons' => [
                'update' => function ($url, Element $model, $key) {
                    if (app()->user->can(Permission::PERMISSION_MANAGE_DASHBOARD, $model->page->project)) {
                        return Html::a(
                            Icon::edit(),
                            ['element/update', 'id' => $model->id]
                        );
                    }
                },
                'remove' => function ($url, Element $model, $key) {
                    if (app()->user->can(Permission::PERMISSION_DELETE, $model)) {
                        return Html::a(
                            Icon::trash(),
                            ['element/delete', 'id' => $model->id],
                            [
                                'data-method' => 'delete',
                                'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this element from the page?')
                            ]
                        );
                    }
                },
            ]
        ]
    ]
]);
Section::end();
