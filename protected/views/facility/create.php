<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;


/**
 * @var \prime\components\View $this
 * @var \prime\models\ar\Facility $model
 */
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->title = Yii::t('app', 'Create facility');

Section::begin()->withHeader(\Yii::t('app', 'Register facility'));
$form = ActiveForm::begin();
echo Form::widget([
    'form' => $form,
    'model' => $model,
    "attributes" => [
        'uuid' => [
            'type' => Form::INPUT_STATIC
        ],
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
                ['label' => \Yii::t('app', 'Register facility'), 'style' => 'primary'],
            ]
        ])
    ],
]);
ActiveForm::end();
Section::end();
