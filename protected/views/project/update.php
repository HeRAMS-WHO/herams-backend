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
    'label' => $model->title,
    'url' => ['project/workspaces', 'id' => $model->id]
];
$this->title = $model->title;


echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

$tabs = [
    [
        'url' => ['project/workspaces', 'id' => $model->id],
        'title' => \Yii::t('app', 'Workspaces'),
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)) {
    $tabs[] =     [
        'url' => ['project/pages', 'id' => $model->id],
        'title' => \Yii::t('app', 'dashboard')
    ];
    $tabs[] = [
        'url' => ['project/update', 'id' => $model->id],
        'title' => \Yii::t('app', 'Settings'),
        'class' => 'active'
    ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $model)) {
    $tabs[] = [
        'url' => ['project/share', 'id' => $model->id],
        'title' => \Yii::t('app', 'Share')
    ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->id
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
        'model' => $model,
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
                'items' => $model->statusOptions()
            ],
            'visibility' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->visibilityOptions()
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