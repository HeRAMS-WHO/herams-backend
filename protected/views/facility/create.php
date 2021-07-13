<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\models\forms\NewFacility;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;

/**
 * @var \prime\components\View $this
 * @var NewFacility $model
 */
$this->params['breadcrumbs'][] = [
    'label' => $model->getWorkspace()->projectTitle(),
    'url' => ['project/workspaces', 'id' => $model->getWorkspace()->projectId()]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->getWorkspace()->title(),
    'url' => ['workspace/limesurvey', 'id' => $model->getWorkspace()->id()]
];

$this->title = Yii::t('app', 'Create facility');

Section::begin()->withHeader(\Yii::t('app', 'Register facility'));
$form = ActiveForm::begin();
echo Form::widget([
    'form' => $form,
    'model' => $model,
    "attributes" => [
        'name' => [
            'type' => Form::INPUT_TEXT,
        ],
        'alternative_name' => [
            'type' => Form::INPUT_TEXT,
        ],
        'code' => [
            'type' => Form::INPUT_TEXT,
        ],
        'coordinates' => [
            'type' => Form::INPUT_TEXT,
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                ['label' => \Yii::t('app', 'Register facility'), 'style' => 'primary'],
            ]
        ])
    ],
]);
ActiveForm::end();
Section::end();
