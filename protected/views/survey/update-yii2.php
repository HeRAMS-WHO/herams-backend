<?php

declare(strict_types=1);

use herams\common\values\SurveyId;
use prime\widgets\Section;
use prime\widgets\surveyJs\Creator2 as Creator2;
use yii\web\View;

/**
 * @var View $this
 * @var SurveyId $surveyId
 */
$this->title = \Yii::t('app', 'Update survey');
$this->registerCss(
    <<<CSS
:root {
    --max-site-width:calc(100vw - 40px);
    
    
}

div.content {
    border-radius: 0;
    padding: 0;
}

CSS
);

Section::begin()
    ->withHeader($this->title);
echo Creator2::widget([
    'surveyId' => $surveyId,
]);

Section::end();
