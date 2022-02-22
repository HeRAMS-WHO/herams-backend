<?php

declare(strict_types=1);

use prime\components\View;
use prime\models\forms\facility\CreateForm;
use prime\widgets\Section;
use prime\widgets\survey\Survey;

/**
 * @var View $this
 * @var CreateForm $model
 */

$this->title = Yii::t('app', 'Create facility');

Section::begin()
    ->withHeader($this->title);

Survey::begin()
    ->withConfig($model->getSurvey()->getConfig())
    ->withLanguages($model->getLanguages())
    ->withSubmitRoute(['facility/create', 'workspaceId' => $model->getWorkspaceId()])
;

Survey::end();

Section::end();
