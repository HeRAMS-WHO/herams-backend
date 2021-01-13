<?php

use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\helpers\Url;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\widgets\menu\WorkspaceTabMenu;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Workspace $model
 *
 */



$this->params['breadcrumbs'][] = [
    'label' => $model->project->title,
    'url' => ['project/workspaces', 'id' => $model->project->id]
];

$this->title = \Yii::t('app', 'Workspace {workspace}', [
    'workspace' => $model->title,
]);

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $model,
]);
$this->endBlock();


Section::begin([
    'subject' => $model,
    'header' => \Yii::t('app', 'Health Facilities'),
    'actions' => [
        [
            'icon' => Icon::recycling(),
            'label' => \Yii::t('app', 'Refresh workspace'),
            'link' => ['workspace/refresh', 'id' => $model->id],
            'permission' => Permission::PERMISSION_SURVEY_DATA
        ],
        [
            'icon' => Icon::download_2(),
            'label' => \Yii::t('app', 'Download'),
            'link' => ['workspace/export', 'id' => $model->id],
            'permission' => Permission::PERMISSION_EXPORT
        ]
    ]
]);
echo Html::tag('iframe', '', [
    'src' => $model->getSurveyUrl(),
    'class' => [],
    'style' => [
        'width' => '100%',
        'min-height' => '600px'
        //'height' => '800px'
    ]
]);
Section::end();
