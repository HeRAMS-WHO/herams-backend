<?php

/** @var \prime\models\ar\Project $model */

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-12 col-md-8 col-lg-6  form-content form-bg">
    <?php
    echo Html::tag('h3', \Yii::t('app', 'Update ').' '.$this->title);
    $form = ActiveForm::begin([
        'method' => 'PUT',
        "type" => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'latitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'longitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'status' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->statusOptions()
            ],
            'visibility' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->visibilityOptions()
            ],
            'country' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\select2\Select2::class,
                'options' => [
                'data' => \yii\helpers\ArrayHelper::map(
                        [['alpha3' => '', 'name' => \Yii::t('app', '(Not set)')]] +
                        (new League\ISO3166\ISO3166())->all(), 'alpha3', 'name')
                ]
            ],
            'typemapAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],
            'overridesAsJson' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'rows' => 10
                ]
            ],
            FormButtonsWidget::embed([
                'orientation' => FormButtonsWidget::ORIENTATION_BLOCK,
                'buttons' => [
                    [
                        'label' => \Yii::t('app', 'Update project'),
                        'options' => ['class' => 'btn btn-primary'],
                    ],
                    Html::a(\Yii::t('app', 'Back to list'), ['/project'], ['class' => 'btn btn-default']),
                ]
            ])
        ]
    ]);
    $form->end();
    ?>
</div>