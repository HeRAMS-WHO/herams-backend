<?php

declare(strict_types=1);

use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var \herams\common\values\ProjectId $projectId
 * @var FacilityForTabMenu $tabMenuModel
 * @var \prime\models\survey\SurveyForSurveyJs $survey
 * @var \herams\common\values\FacilityId $facilityId
 */

 
$this->title = Yii::t('app', 'Create update Situation');

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $tabMenuModel,
    ]
);
$this->endBlock();

Section::begin()
    ->withHeader(Yii::t('app', 'Update Situation'));

$survey = Survey::begin()
    ->withConfig($survey->getConfig())
    ->withExtraData([
        'facilityId' => $facilityId,
        'surveyId' => $survey->getId(),
        'response_type' => 'situation',
    ])
    ->withSubmitRoute([
        'update-situation',
        'id' => $facilityId,
    ])
    ->withProjectId($projectId)
    ->withSubmitRoute([
        'api/survey-response/create',
    ])
    ->withRedirectRoute([
        'facility/responses',
        'id' => $facilityId,
    ])
;

Survey::end();

Section::end();
