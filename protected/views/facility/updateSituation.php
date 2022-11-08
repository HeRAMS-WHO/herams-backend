<?php

declare(strict_types=1);

use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\models\forms\facility\UpdateForm;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var UpdateForm $model
 * @var \herams\common\values\ProjectId $projectId
 * @var FacilityForTabMenu $tabMenuModel
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
    ->withConfig($model->getSurvey()->getConfig())
    ->withData($model->data ?? [])
    ->withExtraData([
        'facilityId' => $model->getFacilityId(),
        'surveyId' => $model->getSurvey()->getId()
    ])
    ->withSubmitRoute([
        'update-situation',
        'id' => $model->getFacilityId(),
    ])
    ->withProjectId($projectId)
;

Survey::end();

Section::end();
