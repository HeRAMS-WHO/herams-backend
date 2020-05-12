<?php
declare(strict_types=1);

/**
 * @var \prime\models\ar\Response $storedResponse,
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface $limesurveyResponse
 */


use prime\models\permissions\Permission;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => $storedResponse->workspace->project->title,
    'url' => app()->user->can(Permission::PERMISSION_WRITE, $storedResponse->workspace->project) ? ['project/update', 'id' => $storedResponse->workspace->project->id] : null
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces'),
    'url' => ['/project/workspaces', 'id' => $storedResponse->workspace->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $storedResponse->workspace->title,
    'url' => app()->user->can(Permission::PERMISSION_WRITE, $storedResponse->workspace) ? ['workspace/update', 'id' => $storedResponse->workspace->id] : null
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Responses'),
    'url' => ['workspace/responses', 'id' => $storedResponse->workspace->id]
];
$this->title = \Yii::t('app', 'Compare data for HF {hf}', ['hf' => $storedResponse->hf_id]);
$this->params['breadcrumbs'][] = $this->title;

$options = ['style' => [
    'width' => '32%',
    'display' => 'inline-block',
    'vertical-align' => 'top'
]];
echo Html::beginTag('div', $options);
    echo Html::tag('h1', 'Our latest data');
    echo Html::tag('pre', print_r($storedResponse->data, true));
echo Html::endTag('div');
echo Html::beginTag('div', $options);
    echo Html::tag('h1', 'Our interpretation of the LS data');
    $loader = new \prime\helpers\LimesurveyDataLoader();
    $loader->loadData($limesurveyResponse->getData(), $storedResponse->workspace, $storedResponse);
    echo Html::tag('pre', print_r($storedResponse->data, true));
echo Html::endTag('div');
echo Html::beginTag('div', $options);
    echo Html::tag('h1', 'Data fresh from LS');
    echo Html::tag('pre', print_r($limesurveyResponse->getData(), true));
echo Html::endTag('div');
