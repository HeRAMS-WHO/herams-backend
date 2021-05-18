<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\models\forms\NewFacility;
use prime\models\forms\UpdateFacility;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;


/**
 * @var \prime\components\View $this
 * @var UpdateFacility $model
 */
$this->params['breadcrumbs'][] = [
    'label' => $model->getWorkspace()->projectTitle(),
    'url' => ['project/workspaces', 'id' => $model->getWorkspace()->id()]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->getWorkspace()->title(),
    'url' => ['workspace/limesurvey', 'id' => $model->getWorkspace()->id()]
];

$this->title = Yii::t('app', 'Update facility');

Section::begin()->withHeader(\Yii::t('app', 'Update facility'));
$form = ActiveForm::begin([
    'enableClientValidation' => false,
]);
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
        'coords' => [
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
