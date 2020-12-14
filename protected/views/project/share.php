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
        'title' => \Yii::t('app', 'Workspaces'),
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project)) {
    $tabs[] =     [
        'url' => ['project/pages', 'id' => $project->id],
        'title' => \Yii::t('app', 'dashboard')
    ];
    $tabs[] = [
        'url' => ['project/update', 'id' => $project->id],
        'title' => \Yii::t('app', 'Settings')
    ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $project)) {
    $tabs[] = [
        'url' => ['project/share', 'id' => $project->id],
        'title' => \Yii::t('app', 'Share'),
        'class' => 'active'
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
    echo Html::tag('h3', \Yii::t('app', 'Add permissions'));
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
    <h3><?= \Yii::t('app', 'View user permissions') ?></h3>
    <?php
    echo $model->renderTable();
    ?>
</div>
<?php
echo Html::endTag('div');
echo Html::endTag('div');
?>