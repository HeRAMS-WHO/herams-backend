<?php

declare(strict_types=1);

use prime\components\View;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var \prime\values\SurveyResponseId $id
 * @var SurveyForSurveyJsInterface | null $survey
 * @var View $this
 * @var null|object $model
 */
assert($this instanceof View);

$this->beginBlock('tabs');
//echo WorkspaceTabMenu::widget([
//    'workspace' => $tabMenuModel,
//]);
$this->endBlock();

$this->title = \Yii::t('app', 'View response');
Section::begin()
    ->withSubject($id)
    ;


Survey::begin()
    ->withProjectId($projectId)
    ->inDisplayMode()
    ->withDataRoute([
        '/api/survey-response/view',
        'id' => $id,
    ])
    ->withConfig($survey->getConfig());

Survey::end();

Section::end();
