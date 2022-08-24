<?php

declare(strict_types=1);

use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\models\forms\facility\UpdateForm;
use prime\values\FacilityId;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var UpdateForm $model
 * @var FacilityForTabMenu $tabMenuModel
 * @var FacilityId $id
 * @var \prime\values\ProjectId $projectId
 * @var \prime\interfaces\survey\SurveyForSurveyJsInterface $survey
 */

$this->title = $tabMenuModel->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $tabMenuModel,
    ]
);
$this->endBlock();

Section::begin()
    ->withHeader(Yii::t('app', 'Update facility'));

$survey = Survey::begin()
    ->withConfig($survey->getConfig())
    ->withDataRoute(['/api/facility/view', 'id' => $id], ['adminData'])
    ->withProjectId($projectId)
    ->inDisplayMode()

;

Survey::end();

Section::end();
