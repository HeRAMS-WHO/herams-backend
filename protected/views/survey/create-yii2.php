<?php

declare(strict_types=1);

use prime\widgets\Section;
use prime\widgets\surveyJs\Creator2 as Creator2;
use yii\web\View;

/**
 * @var View $this
 */

$this->title = \Yii::t('app', 'Create survey');

$this->registerCss(
    <<<CSS
:root {
    --max-site-width: 100vw;
}

CSS
);

Section::begin()
    ->withHeader($this->title);

echo Creator2::widget([
]);

Section::end();
