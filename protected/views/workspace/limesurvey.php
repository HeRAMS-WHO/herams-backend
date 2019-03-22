<?php

use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Workspace $model
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['site/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces for {project}', [
        'project' => $model->project->title
    ]),
    'url' => ['project/workspaces', 'id' => $model->project->id]
];
$this->title = \Yii::t('app', 'Update data for {workspace}', [
    'workspace' => $model->getName()
]);
$this->params['breadcrumbs'][] = $this->title;
//echo Html::beginTag('div', [
//    'class' => ['full-page'],
//    'style' => [
//        'position' => 'relative'
//    ]
//]);
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
//echo Html::endTag('div');