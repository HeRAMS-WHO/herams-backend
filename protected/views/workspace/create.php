<?php

declare(strict_types=1);

use prime\components\View;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var \herams\common\values\ProjectId $projectId
 * @var \prime\interfaces\survey\SurveyForSurveyJsInterface $survey
 *
 * @var View $this
 */
assert($this instanceof View);

$this->title = \Yii::t('app', "Create new workspace");

$this->beginBlock('tabs');
$this->endBlock();

Section::begin()
;

$survey = Survey::begin()
    ->withConfig($survey->getConfig())
    ->withProjectId($projectId)
    ->withExtraData([
        'projectId' => $projectId,
    ])
    ->withSubmitRoute([
        '/api/workspace/create',
    ])
    ->withServerValidationRoute(['/api/workspace/validate'])
    ->withRedirectRoute([
        'project/workspaces',
        'id' => $projectId,
    ])
;

Survey::end();

Section::end();
