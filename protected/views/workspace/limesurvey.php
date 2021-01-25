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

$language = \Yii::$app->language;
\prime\assets\IframeResizeBundle::register($this);
Section::begin([
    'subject' => $model,
    'header' => \Yii::t('app', 'Health Facilities'),
    'actions' => [
        [
            'linkOptions' => ['target' => 'limesurvey'],
            'icon' => Icon::add(),
            'label' => \Yii::t('app', 'Register new health facility'),
            'link' => "https://ls.herams.org/391149?ResponsePicker=new&token={$model->token}&lang={$language}&newtest=Y",
            'permission' => Permission::PERMISSION_CREATE_FACILITY
        ],
    ]
]);
echo Html::tag('iframe', '', [
    'name' => 'limesurvey',
    'src' => $model->getSurveyUrl(),
    'class' => [],
    'style' => [
        'width' => '100%',
        'min-height' => '600px'
        //'height' => '800px'
    ]
]);
$this->registerJs('iFrameResize({ log: true}, "iframe[name=limesurvey]");');
Section::end();
