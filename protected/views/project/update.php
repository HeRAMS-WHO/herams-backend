<?php

/** @var \prime\models\ar\Project $model */

use app\components\Form;
use app\components\ActiveForm;
use prime\models\ar\Permission;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\TabMenu;
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

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project)) {
    $tabs[] =
        [
            'url' => ['project/pages', 'id' => $project->id],
            'title' => \Yii::t('app', 'Dashboard settings')
        ];
    $tabs[] =
        [
            'url' => ['project/update', 'id' => $project->id],
            'title' => \Yii::t('app', 'Settings')
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
echo Html::beginTag('div', ['class' => "content"]);
?>
<div class="form-content form-bg">
    <?php
    echo Html::tag('h3', \Yii::t('app', 'Update ').' '.$this->title);
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
                    ],
                    Html::a(\Yii::t('app', 'Back to list'), ['/project'], ['class' => 'btn btn-default']),
                ]
            ])
        ]
    ]);
    $form->end();
    ?>
</div>
<?php
echo Html::endTag('div');
echo Html::endTag('div');
?>