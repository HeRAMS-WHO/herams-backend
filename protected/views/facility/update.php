<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\models\forms\UpdateFacility;
use prime\widgets\FormButtonsWidget;
use prime\widgets\LocalizableInput;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var UpdateFacility $model
 * @var FacilityForTabMenu $tabMenuModel
 */

$this->title = $tabMenuModel->title();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    ['facility' => $tabMenuModel]
);
$this->endBlock();

Section::begin()->withHeader(Yii::t('app', 'Update facility'));
$survey = Survey::begin()
    ->withSubmitRoute(['facility/update', 'id' => $model->getId()])
;
$survey->data = [
    'name' => $model->name
];
Survey::end();
$form = ActiveForm::begin([
    'enableClientValidation' => true,
]);
ActiveForm::end();
Section::end();
