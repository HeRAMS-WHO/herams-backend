<?php

use kartik\builder\Form;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \prime\models\forms\user\RequestResetForm $model
 */

$this->title = Yii::t('app', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideMenu'] = true;
    echo Html::tag('header', $this->title);
    echo Form::widget([
        'model' => $model,
        'form' => $form = ActiveForm::begin([
            'id' => 'reset-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'validateOnBlur' => true,
            'validateOnType' => false,
            'validateOnChange' => false,
        ]),
        'columns' => 1,
        'attributes' => [

            'email' => ['type' => Form::INPUT_TEXT],
        ]
    ]);
    ActiveForm::end();
    echo Html::beginTag('div', ['class' => 'actions']);
    echo Html::a(Yii::t('app', 'Back to sign in'), ['/session/create']);
    echo Html::submitButton(Yii::t('app', 'Request password reset'), ['class' => 'btn btn-primary', 'form' => 'reset-form']);
    echo Html::endTag('div');
