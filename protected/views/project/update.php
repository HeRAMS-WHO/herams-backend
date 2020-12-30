<?php

/** @var \prime\models\ar\Project $model */

use app\components\Form;
use app\components\ActiveForm;
use prime\models\ar\Permission;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\TabMenu;
use prime\helpers\Icon;
use yii\helpers\Url;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/workspaces', 'id' => $project->id]
];
$this->title = $project->title;


echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

$tabs = [
    [
        'url' => ['project/workspaces', 'id' => $project->id],
        'title' => \Yii::t('app', 'Workspaces') . " ({$project->workspaceCount})"
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_MANAGE_DASHBOARD, $project)) {
    $tabs[] =
        [
            'url' => ['project/pages', 'id' => $project->id],
            'title' => \Yii::t('app', 'Dashboard settings')
        ];
    $tabs[] =
        [
            'url' => ['project/update', 'id' => $project->id],
            'title' => \Yii::t('app', 'Project settings')
        ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $project)) {
    $tabs[] =
        [
            'url' => ['project/share', 'id' => $project->id],
            'title' => \Yii::t('app', 'Users') . " ({$project->contributorCount})"
        ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project)) {
    $tabs[] =
        [
            'url' => ['/admin/limesurvey'],
            'title' => \Yii::t('app', 'Backend administration')
        ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->uniqueId
]);
echo Html::beginTag('div', ['class' => count($tabs) <= 1 ? 'content no-tab': 'content']);

echo Html::beginTag('div', ['class' => 'action-group']);
if (app()->user->can(Permission::PERMISSION_DELETE, $project)) {
    echo Html::a(
        Icon::trash() . \Yii::t('app', 'Delete'),
        ['project/delete', 'id' => $project->id],
        [
            'data-method' => 'delete',
            'title' => \Yii::t('app', 'Delete'),
            'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this project from the system?'),
            'class' => 'btn btn-delete btn-icon'
        ]
    );
}
echo Html::endTag('div');

echo Html::tag('h4', \Yii::t('app', 'Update project'));
echo Html::beginTag('div', ['class' => 'form-content form-bg']);
$form = ActiveForm::begin([
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
    'model' => $project,
    'columns' => 1,
    "attributes" => [
        'title' => [
            'type' => Form::INPUT_TEXT,
        ],
        'latitude' => [
            'type' => Form::INPUT_TEXT
        ],
        'longitude' => [
            'type' => Form::INPUT_TEXT
        ],
        'status' => [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => $project->statusOptions()
        ],
        'visibility' => [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => $project->visibilityOptions()
        ],
        'country' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\select2\Select2::class,
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(
                    [['alpha3' => '', 'name' => \Yii::t('app', '(Not set)')]] +
                        (new League\ISO3166\ISO3166())->all(),
                    'alpha3',
                    'name'
                )
            ]
        ],
        'typemapAsJson' => [
            'type' => Form::INPUT_TEXTAREA,
            'options' => [
                'rows' => 10
            ]
        ],
        'overridesAsJson' => [
            'type' => Form::INPUT_TEXTAREA,
            'options' => [
                'rows' => 10
            ]
        ],
        FormButtonsWidget::embed([
            'orientation' => FormButtonsWidget::ORIENTATION_RIGHT,
            'buttons' => [
                [
                    'label' => \Yii::t('app', 'Update project'),
                    'options' => ['class' => 'btn btn-primary'],
                ]
            ]
        ])
    ]
]);
$form->end();
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
