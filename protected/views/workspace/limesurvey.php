<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\widgets\menu\WorkspaceTabMenu;

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
    'url' => ['workspace/limesurvey', 'id' => $model->id]
];
$this->title = \Yii::t('app', 'Workspace {workspace}', [
    'workspace' => $model->title,
]);

echo WorkspaceTabMenu::widget([
    'workspace' => $model,
    'currentPage' => $this->context->action->uniqueId
]);

echo Html::beginTag('div', ['class' => "content"]);
echo Html::beginTag('div', ['class' => 'action-group']);

if (\Yii::$app->user->can(Permission::PERMISSION_SURVEY_DATA, $model)) {
    echo Html::a(Icon::recycling() . \Yii::t('app', 'Refresh workspace'), Url::to(['workspace/refresh', 'id' => $model->id]), ['class' => 'btn btn-default btn-icon']);
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
