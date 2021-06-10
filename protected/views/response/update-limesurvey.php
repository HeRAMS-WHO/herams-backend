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
 * @var \prime\models\response\ResponseForSurvey $model
 *
 */



//$this->params['breadcrumbs'][] = [
//    'label' => $model->project->title,
//    'url' => ['project/workspaces', 'id' => $model->project->id]
//];

//$this->title = \Yii::t('app', 'Workspace {workspace}', [
//    'workspace' => $model->title,
//]);

$language = \Yii::$app->language;
\prime\assets\IframeResizeBundle::register($this);
Section::begin([
    'subject' => $model,
    'header' => \Yii::t('app', 'Limesurvey'),

]);
echo Html::tag('iframe', '', [
    'name' => 'limesurvey',
    'src' => $model->getLimesurveyUrl(),
    'class' => [],
    'style' => [
        'width' => '100%',
        'min-height' => '600px'
    ]
]);
$this->registerJs('iFrameResize({ log: false, checkOrigin: ["https://ls.herams.org"]}, "iframe[name=limesurvey]");');
Section::end();
