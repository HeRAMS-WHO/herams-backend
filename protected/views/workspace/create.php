<?php

declare(strict_types=1);

use prime\components\View;
use prime\widgets\Section;
use prime\widgets\survey\SurveyFormWidget;

/**
 * @var \prime\interfaces\SurveyFormInterface $form
 * @var View $this
 */
assert($this instanceof View);

$this->title = \Yii::t('app', "Create new workspace");

$this->beginBlock('tabs');
$this->endBlock();

Section::begin();


$survey = SurveyFormWidget::begin()
    ->withForm($form)
;

SurveyFormWidget::end();

Section::end();
