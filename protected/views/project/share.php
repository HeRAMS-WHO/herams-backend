<?php

use app\components\ActiveForm;
use app\components\Form;
use prime\models\ar\Permission;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\TabMenu;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\Project $project
 * @var \prime\models\forms\Share $model
 */

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
echo Html::endTag('div');
?>

<div class="form-content form-bg">
    <?php
    echo Html::tag('h3', \Yii::t('app', 'Add new user'));
    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3

        ]
    ]);
    echo $model->renderForm($form);
    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'attributes' => [
            FormButtonsWidget::embed([
                'options' => [
                    'class' => [
                        'pull-right'
                    ],
                ],
                'buttons' => [
                    ['label' => \Yii::t('app', 'Add'), 'options' => ['class' => ['btn', 'btn-primary']]]
                ]
            ])
        ]
    ]);
    $form->end();
    ?>
</div>
<div class="form-content form-bg full-width">
    <h3><?= \Yii::t('app', 'View current users') ?></h3>
    <?php
    echo $model->renderTable();
    ?>
</div>
<?php
echo Html::endTag('div');
echo Html::endTag('div');
?>