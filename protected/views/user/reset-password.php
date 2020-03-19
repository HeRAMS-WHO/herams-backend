<?php

use collecthor\bundles\AppAssetBundle;
use kartik\builder\Form;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */

$this->title = Yii::t('app', 'Reset your password');
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideMenu'] = true;
    echo Form::widget([
        'model' => $model,
        'form' => $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'validateOnBlur' => true,
            'validateOnType' => false,
            'validateOnChange' => false,
            'type' => ActiveForm::TYPE_VERTICAL,
            'formConfig' => [
                'showLabels' => ActiveForm::SCREEN_READER
            ],
            'fieldConfig' => [
                'autoPlaceholder' => true,
            ]
        ]),
        'columns' => 1,
        'attributes' => [

            'password' => ['type' => Form::INPUT_PASSWORD],
            'password_repeat' => ['type' => Form::INPUT_PASSWORD],
        ]
    ]);
    echo Html::beginTag('div', ['class' => 'actions']);
    echo Html::a(Yii::t('app', 'Back to sign in'), ['/session/create']);
    echo Html::submitButton(Yii::t('app', 'Reset password'), ['class' => 'btn btn-primary btn-block', 'tabindex' => '4']);
    echo Html::endTag('div');
    ActiveForm::end();