<?php
declare(strict_types=1);

use prime\assets\IframeResizeBundle;
use prime\models\response\ResponseForSurvey;
use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ResponseForSurvey $model
 */

$language = \Yii::$app->language;
IframeResizeBundle::register($this);

Section::begin()
    ->withHeader(\Yii::t('app', 'Limesurvey'))
    ->withSubject($model);

echo Html::tag('iframe', '', [
    'name' => 'limesurvey',
    'src' => $model->getExternalResponseId()->getLimesurveyUrl(\Yii::$app->language),
    'class' => [],
    'style' => [
        'width' => '100%',
        'min-height' => '600px'
    ]
]);
$this->registerJs('iFrameResize({ log: false, checkOrigin: ["https://ls.herams.org"]}, "iframe[name=limesurvey]");');

Section::end();
