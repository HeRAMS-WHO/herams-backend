<?php

declare(strict_types=1);

use prime\components\ActiveForm;
use app\components\Form;
use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\models\forms\facility\UpdateForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\LocalizableInput;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var UpdateForm $model
 * @var FacilityForTabMenu $tabMenuModel
 */

$this->title = $tabMenuModel->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    ['facility' => $tabMenuModel]
);
$this->endBlock();

Section::begin()
    ->withHeader(Yii::t('app', 'Update facility'));

$survey = Survey::begin()
    ->withConfig($model->getSurvey()->getConfig())
    ->withData($model->data ?? [])
    ->withSubmitRoute(['update-situation', 'id' => $model->getFacilityId()])
    ->withLanguages($model->getLanguages())
;

Survey::end();

Section::end();
