<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use collecthor\bundles\AppAssetBundle;
use dektrium\user\models\LoginForm;
use dektrium\user\widgets\Connect;
use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \prime\models\forms\user\RequestAccountForm $model
 */

$this->title = Yii::t('app', 'Create account');
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideMenu'] = true;

?>
<style>
    body, input {
        text-align: center;
    }
    .panel {
        min-width: 200px;
        margin: 10px;
    }
</style>
<?php
echo Html::tag('header', $this->title);
        echo Form::widget([
            'model' => $model,
            'form' => $form = ActiveForm::begin([
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'validateOnBlur' => true,
                'validateOnType' => true,
                'validateOnChange' => true,
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
                'email' => [
                    'label' => \Yii::t('app', 'Creating a new acount for email:'),
                    'type' => Form::INPUT_STATIC,
                ],
                'name' => [
                    'type' => Form::INPUT_TEXT
                ],
                'password' => [
                    'type' => Form::INPUT_PASSWORD,
                    'options' => [
                        'autocomplete' => 'new-password'
                    ]
                ],
                'confirm_password' => [
                    'type' => Form::INPUT_PASSWORD,
                    'options' => [
                        'autocomplete' => 'new-password'
                    ]
                ],
                FormButtonsWidget::embed([
                    'buttons' => [
                        Html::submitButton(Yii::t('app', 'Create account'), ['class' => ['btn', 'btn-primary', 'btn-block']])
                    ]
                ])

            ]
        ]);
        ActiveForm::end();