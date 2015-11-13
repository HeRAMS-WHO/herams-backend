<?php

use app\components\Html;
use yii\helpers\ArrayHelper;
use app\components\Form;

/**
 * @var \prime\models\ar\UserData $userData
 * @var \yii\web\View $this
 */
echo \app\components\Form::widget([
    'formName' => 'test',
    'columns' => 2,
    'attributes' => [
        'title' => [
            'label' => 'Title',
            'type' => Form::INPUT_TEXT,
            'value' => ArrayHelper::getValue($userData->getData(), 'test.title')
        ],
        'description' => [
            'label' => 'Description',
            'type' => Form::INPUT_TEXTAREA,
            'value' => ArrayHelper::getValue($userData->getData(), 'test.description')
        ],
        'options' => [
            'label' => 'Options',
            'type' => Form::INPUT_CHECKBOX_LIST,
            'value' => ArrayHelper::getValue($userData->getData(), 'test.options'),
            'items' => ['option1' => 'option1', 'option2' => 'option2']
        ]
    ]
]);
