<?php

declare(strict_types=1);

use app\components\Form;
use prime\components\ActiveForm;
use prime\components\View;
use prime\helpers\Icon;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\interfaces\WorkspaceForTabMenu;
use prime\models\ar\Permission;
use prime\models\forms\workspace\UpdateForLimesurvey;
use prime\values\WorkspaceId;
use prime\widgets\ButtonGroup;
use prime\widgets\FormButtonsWidget;
use prime\widgets\LocalizableInput;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;
use yii\bootstrap\Html;

/**
 * @var \prime\values\SurveyResponseId $id
 * @var SurveyForSurveyJsInterface | null $survey
 * @var View $this
 * @var null|object $model
 */
assert($this instanceof View);

$this->beginBlock('tabs');
//echo WorkspaceTabMenu::widget([
//    'workspace' => $tabMenuModel,
//]);
$this->endBlock();

$this->title = \Yii::t('app', 'View response');
Section::begin()
    ->withSubject($id)
    ;


Survey::begin()
    ->withProjectId($projectId)
    ->inDisplayMode()
    ->withDataRoute([
        '/api/survey-response/view',
        'id' => $id,
    ])
    ->withConfig($survey->getConfig());

Survey::end();

Section::end();
