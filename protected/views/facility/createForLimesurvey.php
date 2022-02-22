<?php

declare(strict_types=1);

use prime\assets\IframeResizeBundle;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\widgets\Section;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var WorkspaceForLimesurvey $model
 */

$language = \Yii::$app->language;
IframeResizeBundle::register($this);

$this->title = \Yii::t('app', 'Create facility');

Section::begin()
    ->withHeader($this->title);

echo Html::tag('iframe', '', [
    'name' => 'limesurvey',
    'src' => "https://ls.herams.org/{$model->project->base_survey_eid}?ResponsePicker=new&token={$model->token}&lang={$language}&newtest=Y",
    'style' => [
        'width' => '100%',
        'min-height' => '600px'
        //'height' => '800px'
    ]
]);

$this->registerJs('iFrameResize({ log: true, scrolling: true }, "iframe[name=limesurvey]");');

Section::end();
