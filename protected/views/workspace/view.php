<?php

use app\components\ActiveForm;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use prime\helpers\Icon;

/**
 * @var \yii\web\View $this
 *
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces for {project}', [
        'project' => $model->project->title
    ]),
    'url' => ['project/workspaces', 'id' => $model->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspace {workspace}', [
        'workspace' => $model->title,
    ]),
    'url' => ['workspaces/view', 'id' => $model->id]
];
$this->title = \Yii::t('app', 'Workspace {workspace}', [
    'workspace' => $model->title,
]);
//$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('div', ['class' => 'topbar']);
echo Html::beginTag('div', ['class' => 'pull-left']);
echo Html::a('Data', ['workspace/view', 'id' => $model->id], ['title' => \Yii::t('app', 'Workspace datas'), 'class' => 'btn btn-white selected']);
echo Html::a('Sharing', ['workspace/share', 'id' => $model->id], ['title' => \Yii::t('app', 'share Workspace '), 'class' => 'btn btn-white']);
echo Html::a('Settings', ['workspace/update', 'id' => $model->id], ['title' => \Yii::t('app', 'update Workspace'), 'class' => 'btn btn-white']);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'btn-group pull-right']);
echo Html::a(Icon::project(), ['project/view', 'id' => $model->project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle pull-right']);
echo Html::beginTag('div', ['class' => 'count']);
echo Icon::list();
echo Html::tag('span', \Yii::t('app', 'Health Facilities'));
echo Html::tag('em', $model->facilityCount);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::contributors();
echo Html::tag('span', \Yii::t('app', 'Contributors'));
echo Html::tag('em', $model->contributorCount);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::sync();
echo Html::tag('span', \Yii::t('app', 'Latest update'));
echo Html::tag('em', $model->latestUpdate);
echo Html::endTag('div');


echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);
echo Html::tag('iframe', '', [
    'src' => $model->getSurveyUrl(),
    'class' => [],
    'style' => [
        'position' => 'absolute',
        'left' => 0,
        'top' => 0,
        'width' => '100%',
        'height' => '100%'
        //'height' => '800px'
    ]
]);
