<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\components\View;
use prime\models\forms\NewFacility;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var NewFacility $model
 */

$this->title = Yii::t('app', 'Create facility');

Section::begin()
    ->withHeader(\Yii::t('app', 'Register facility'));

Survey::begin()
    ->withSubmitRoute(['facility/create', 'workspaceId' => $model->getWorkspace()->id()])
    ->withLanguages($model->getWorkspace()->languages())
;

Survey::end();
Section::end();
