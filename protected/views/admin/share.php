<?php

use app\components\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use prime\models\ar\Permission;
use prime\widgets\menu\TabMenu;

/**
 * @var \prime\models\ar\Workspace $workspace
 * @var \prime\models\forms\Share $model
 */
$this->title = \Yii::t('app', 'Administration');
$this->params['breadcrumbs'][] = ['label' => ""];

$tabs = [
    [
        'url' => ['project/index'],
        'title' => \Yii::t('app', 'Projects')
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN)) {
    $tabs[] =
        [
            'url' => ['user/index'],
            'title' => \Yii::t('app', 'Users')
        ];
    $tabs[] =
        [
            'url' => ['admin/share'],
            'title' => \Yii::t('app', 'Global permissions')
        ];
    $tabs[] =
        [
            'url' => ['admin/limesurvey'],
            'title' => \Yii::t('app', 'Backend administration')
        ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->uniqueId
]);

echo Html::beginTag('div', ['class' => 'content']);
echo Html::beginTag('div', ['class' => 'action-group']);
echo Html::a(\Yii::t('app', 'Projects'), Url::to(['project/index']), ['class' => 'btn btn-default']);
echo Html::endTag('div');
?>
<div class="col-xs-12">
    <h4><?= \Yii::t('app', 'Add user') ?></h4>
    <?php
    $form = ActiveForm::begin([
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3
        ]
    ]);

    echo $model->renderForm($form);
    ?>
    <div class="col-xs-offset-11"><button type="submit" class="btn btn-primary">Share</button></div>
    <?php
    $form->end();
    ?>
    <h4><?= \Yii::t('app', 'Already shared with') ?></h4>
    <?php
    echo $model->renderTable();
    ?>
</div>
<?php
echo Html::endTag('div');