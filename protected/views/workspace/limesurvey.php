<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\widgets\menu\TabMenu;

/**
 * @var \yii\web\View $this
 *
 */



$this->params['breadcrumbs'][] = [
    'label' => $model->project->title,
    'url' => ['project/workspaces', 'id' => $model->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspace {workspace}', [
        'workspace' => $model->title,
    ]),
    'url' => ['workspaces/limesurvey', 'id' => $model->id]
];
$this->title = \Yii::t('app', 'Workspace {workspace}', [
    'workspace' => $model->title,
]);

echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

$tabs = [];

if (\Yii::$app->user->can(Permission::PERMISSION_SURVEY_DATA, $model)) {
    $tabs[] =
        [
            'url' => ["workspace/limesurvey", 'id' => $model->id],
            'title' => \Yii::t('app', 'Health Facilities') . " ({$model->facilityCount})"
        ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)) {
    $tabs[] =
        [
            'url' => ["workspace/update", 'id' => $model->id],
            'title' => \Yii::t('app', 'Workspace settings')
        ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $model)) {
    $tabs[] =
        [
            'url' => ["workspace/share", 'id' => $model->id],
            'title' => \Yii::t('app', 'Users') . " ({$model->contributorCount})"
        ];
}

if ($model->responseCount > 0 && \Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)) {
    $tabs[] =
        [
            'url' => ['workspace/responses', 'id' => $model->id],
            'title' => \Yii::t('app', 'Responses')
        ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->uniqueId
]);



echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);
echo Html::beginTag('div', ['class' => 'action-group']);

if (\Yii::$app->user->can(Permission::PERMISSION_SURVEY_DATA, $model)) {
    echo Html::a(Icon::recycling() . \Yii::t('app', 'refresh workspace'), Url::to(['workspace/refresh', 'id' => $model->id]), ['class' => 'btn btn-default btn-icon']);
}
if (\Yii::$app->user->can(Permission::PERMISSION_EXPORT, $model)) {
    echo Html::a(Icon::download_2() . \Yii::t('app', 'Download'), ['workspace/export', 'id' => $model->id], ['class' => 'btn btn-default btn-icon']);
}
echo Html::endTag('div');
echo Html::tag('h4', \Yii::t('app', 'Health facilities'));
echo Html::tag('iframe', '', [
    'src' => $model->getSurveyUrl(),
    'class' => [],
    'style' => [
        'width' => '100%',
        'min-height' => '600px'
        //'height' => '800px'
    ]
]);


echo Html::endTag('div');
echo Html::endTag('div');
