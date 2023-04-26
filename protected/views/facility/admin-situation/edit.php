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

 //$this->params['subject'] = Icon::healthFacility() . $tabMenuModel->getTitle();
$this->title = 'Edit Situation';


Section::begin()
    ->withHeader(Yii::t('app', 'Edit Admin Situation'));
$survey = Survey::begin()
    ->withConfig($survey->getConfig())
    ->withDataRoute([
        '/api/facility/view-admin-situation',
        'id' => $cid,
    ], ['data'])
    ->withExtraData([
        'surveyResponseId' => $surveyResponseId,
        'facilityId' => $facilityId,
        'response_type' => 'admin',
    ])
    ->withSubmitRoute([
        'edit-admin-situation',
        'id' => $facilityId,
    ])
    ->withProjectId($projectId)
    ->withSubmitRoute([
        'api/facility/save-situation',
        'id' => $cid,
    ])
    ->withRedirectRoute([
        'facility/responses',
        'id' => $facilityId,
    ])
;

Survey::end();

Section::end();
