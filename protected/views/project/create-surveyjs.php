<?php

declare(strict_types=1);

use prime\components\View;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var \prime\modules\Api\models\NewProject $model
 * @var \prime\interfaces\survey\SurveyForSurveyJsInterface $survey
 * @var View $this
 */
assert($this instanceof View);

$this->title = \Yii::t('app', "Create new project");

$this->beginBlock('tabs');
$this->endBlock();

Section::begin()
;

$survey = Survey::begin()
    ->withConfig($survey->getConfig())
    ->withSubmitRoute([
        '/api/project/create',
    ])
    ->withServerValidationRoute(['/api/project/validate'])
    ->withRedirectRoute([
        'project/index',
    ])
;

Survey::end();

Section::end();
