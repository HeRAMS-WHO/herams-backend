<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\models\forms\NewFacility;
use prime\models\forms\UpdateFacility;
use prime\widgets\FormButtonsWidget;
use prime\widgets\LocalizableInput;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;


/**
 * @var \prime\components\View $this
 * @var UpdateFacility $model
 * @var \prime\interfaces\FacilityForTabMenu $tabMenuModel
 */

echo $this->render('_breadcrumbs', ['tabMenuModel' => $tabMenuModel]);
$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    ['facility' => $tabMenuModel]
);
$this->endBlock();

Section::begin()->withHeader(Yii::t('app', 'Update facility'));
$form = ActiveForm::begin([
    'enableClientValidation' => true,
]);
echo Form::widget([
    'form' => $form,
    'model' => $model,
    "attributes" => [
        'name' => [
            'type' => Form::INPUT_TEXT,
        ],
        'i18nName' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => LocalizableInput::class,
        ],
        'alternative_name' => [
            'type' => Form::INPUT_TEXT,
        ],
        'i18nAlternative_name' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => LocalizableInput::class,
        ],
        'code' => [
            'type' => Form::INPUT_TEXT,
        ],
        'coordinates' => [
            'type' => Form::INPUT_TEXT,
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                ['label' => \Yii::t('app', 'Update facility'), 'style' => 'primary'],
            ]
        ])
    ],
]);
ActiveForm::end();
Section::end();
