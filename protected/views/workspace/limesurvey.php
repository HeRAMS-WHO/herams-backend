<?php
declare(strict_types=1);

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
            'link' => "https://ls.herams.org/{$model->project->base_survey_eid}?ResponsePicker=new&token={$model->token}&lang={$language}&newtest=Y",
            'permission' => Permission::PERMISSION_CREATE_FACILITY
        ],
        [
            'icon' => Icon::recycling(),
            'label' => \Yii::t('app', 'Refresh workspace'),
            'link' => ['workspace/refresh', 'id' => $model->id],
            'permission' => Permission::PERMISSION_SURVEY_DATA
        ],
    ]
]);
echo Html::tag('iframe', '', [
    'name' => 'limesurvey',
    'src' => $model->getSurveyUrl(\Yii::$app->user->can(Permission::PERMISSION_SURVEY_DATA, $model)),
    'class' => [],
    'style' => [
        'width' => '100%',
        'min-height' => '600px'
        //'height' => '800px'
    ]
]);
$this->registerJs('iFrameResize({ log: true, scrolling: true }, "iframe[name=limesurvey]");');
Section::end();
