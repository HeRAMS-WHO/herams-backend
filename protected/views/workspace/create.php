<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use kartik\select2\Select2;
use prime\models\forms\workspace\Create;
use prime\models\forms\workspace\CreateForLimesurvey;
use prime\widgets\ButtonGroup;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var CreateForLimesurvey|Create $model
 */

$this->title = \Yii::t('app', 'Create workspace');

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin();

$attributes = [
    'title' => [
        'type' => Form::INPUT_TEXT,
    ],
];

if ($model instanceof CreateForLimesurvey) {
    $attributes['token'] = [
        'type' => Form::INPUT_WIDGET,
        'widgetClass' => Select2::class,
        'options' => [
            'data' => $model->tokenOptions(),
        ],
    ];
}

$attributes[] = FormButtonsWidget::embed([
    'buttons' => [
        ['label' => \Yii::t('app', 'Create workspace'), 'type' => ButtonGroup::TYPE_SUBMIT, 'style' => 'primary'],
    ]
]);

echo Form::widget([
    'form' => $form,
    'model' => $model,
    'columns' => 1,
    'attributes' => $attributes,
]);

ActiveForm::end();

Section::end();
