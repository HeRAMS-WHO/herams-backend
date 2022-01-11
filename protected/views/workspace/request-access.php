<?php

declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\components\View;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\accessRequest\Create;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\helpers\Html;

/**
 * @var Create $model
 * @var WorkspaceForLimesurvey $workspace
 * @var View $this
 */

$this->title = $workspace->title;

Section::begin()
    ->withHeader(Yii::t('app', 'Request access'), ['style' => ['display' => 'block']]);

echo Html::tag('p', \Yii::t('app', 'Explain what permissions you need and why you need them.'));

/** @var ActiveForm $form */
$form = ActiveForm::begin([

]);

echo Form::widget([
    'form' => $form,
    'model' => $model,
    "attributes" => [
        'project' => [
            'type' => Form::INPUT_STATIC,
            'label' => \Yii::t('app', 'Workspace'),
            'staticValue' => $workspace->title,
        ],
        'subject' => [
            'type' => Form::INPUT_TEXT,
        ],
        'body' => [
            'type' => Form::INPUT_TEXTAREA
        ],
        'permissions' => [
            'type' => Form::INPUT_CHECKBOX_LIST,
            'items' => $model->getPermissionOptions(),
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                ['label' => Yii::t('app', 'Request'), 'style' => 'primary'],
            ],
        ]),
    ]
]);

ActiveForm::end();
Section::end();
