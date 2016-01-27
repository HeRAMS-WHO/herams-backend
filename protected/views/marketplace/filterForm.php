<?php

use app\components\ActiveForm;
use app\components\Form;
use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\MarketplaceFilter $filter
 */

$form = ActiveForm::begin([
    'id' => 'marketplace-filter',
    'method' => 'GET',
    'type' => ActiveForm::TYPE_VERTICAL,
]);

echo Form::widget([
    'form' => $form,
    'model' => $filter,
    'columns' => 3,
    'attributes' => [
        'regions' => [
            'type' => Form::INPUT_CHECKBOX_LIST,
            'items' => $filter->regionOptions()
        ],
        'endDate' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\DatePicker::class,
            'options' => [
                'pluginOptions' => [
                    'format' => \prime\models\forms\MarketplaceFilter::DATE_FORMAT_JS
                ]
            ]
        ],
        'structures' => [
            'type' => Form::INPUT_CHECKBOX_LIST,
            'items' => $filter->structureOptions()
        ]
    ]
]);

echo Form::widget([
    'form' => $form,
    'model' => $filter,
    'attributes' => [
        'actions' => [
            'type' => Form::INPUT_RAW,
            'value' => Html::tag('div',
                Html::a(\Yii::t('app', 'Clear'), ['marketplace/map'], ['class' => 'btn btn-default']) . ' ' .
                Html::submitButton(\Yii::t('app', 'Focus'), ['class' => 'btn btn-primary'])
                ,[
                    'class' => 'text-right'
                ])
        ]
    ]
]);

$form->end();