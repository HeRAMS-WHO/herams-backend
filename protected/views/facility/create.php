<?php

declare(strict_types=1);

use prime\components\View;
use prime\models\forms\facility\CreateForm;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var \prime\interfaces\survey\SurveyForSurveyJsInterface $survey
 * @var \prime\values\ProjectId $projectId
 * @var \prime\values\WorkspaceId $workspaceId
 */

$this->title = Yii::t('app', 'Create facility');

Section::begin()
    ->withHeader($this->title);

Survey::begin()
    ->withConfig($survey->getConfig())
    ->withProjectId($projectId)
    ->withExtraData([
        'workspaceId' => $workspaceId,
    ])
    ->withSubmitRoute([
        '/api/facility/create',
    ])
    ->withServerValidationRoute([
        '/api/facility/validate',
        'workspace_id' => $workspaceId,
    ])
    ->withRedirectRoute([
        '/workspace/facilities',
        'id' => $workspaceId,
    ])
;

Survey::end();

Section::end();
