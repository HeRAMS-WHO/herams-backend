<?php

declare(strict_types=1);

use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\models\forms\facility\UpdateForm;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var UpdateForm $model
 * @var FacilityForTabMenu $tabMenuModel
 * @var FacilityId $id
 * @var ProjectId $projectId
 * @var SurveyForSurveyJsInterface $survey
 */

$this->title = $tabMenuModel->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $tabMenuModel,
    ]
);
$this->endBlock();

Section::begin();
$survey = Survey::begin()
    ->withConfig($survey->getConfig())
    ->withDataRoute([
        '/api/facility/view',
        'id' => $id,
    ], ['admin_data'])
    ->withProjectId($projectId)
    ->inDisplayMode()

;

Survey::end();

Section::end();
