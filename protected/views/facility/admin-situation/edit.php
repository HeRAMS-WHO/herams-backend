<?php

declare(strict_types=1);

use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;
use prime\helpers\Icon;

/**
 * @var View $this
 * @var \herams\common\values\ProjectId $projectId
 * @var FacilityForTabMenu $tabMenuModel
 * @var \prime\models\survey\SurveyForSurveyJs $survey
 * @var \herams\common\values\FacilityId $facilityId
 */
$this->params['subject'] =  'Edit Admin Situation';

$this->title = 'Edit Admin Situation';

$surveyConfig = $survey->getConfig();

Section::begin()
    ->withHeader(Yii::t('app', 'Edit Admin Situation'));
$survey = Survey::begin()
    ->withConfig($surveyConfig)
    ->withDataRoute([
        '/api/facility/view-situation',
        'id' => $cid,
    ], ['data'])
    ->withExtraData([
        'surveyResponseId' => $surveyResponseId,
        'facilityId' => $facilityId,
        'response_type' => 'admin',
        'response_id' => $cid, //respose id for validation
    ])
    ->withProjectId($projectId)
    ->withSubmitRoute([
        'api/facility/save-situation',
        'id' => $cid,
    ])
    ->withRedirectRoute([
        'facility/admin-responses',
        'id' => $facilityId,
    ])
    ->withServerValidationRoute([
        'api/facility/validate-situation',
        'id' => $facilityId,
        
    ])
;

Survey::end();

Section::end();
