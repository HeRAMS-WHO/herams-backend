<?php

declare(strict_types=1);

use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var \herams\common\values\ProjectId $projectId
 * @var FacilityForTabMenu $tabMenuModel
 * @var \prime\models\survey\SurveyForSurveyJs $survey
 * @var \herams\common\values\FacilityId $facilityId
 */

$this->title = 'View Admin Situation';


$surveyConfig = $survey->getConfig();

Section::begin();
$survey = Survey::begin()
    ->withConfig($surveyConfig)
    ->withDataRoute([
        '/api/facility/view-situation',
        'id' => $cid,
    ], ['data'])
    ->withProjectId($projectId)
    ->inDisplayMode()

;

Survey::end();

Section::end();
