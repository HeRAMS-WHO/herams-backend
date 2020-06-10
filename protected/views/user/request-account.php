<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \prime\models\forms\user\RequestAccountForm $model
 */

$this->title = Yii::t('app', 'Sign up for the HeRAMS platform');
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideMenu'] = true;

echo Html::tag('header', $this->title);
    echo Form::widget([
        'model' => $model,
        'form' => $form = ActiveForm::begin([
            'id' => 'request-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'validateOnBlur' => true,
            'validateOnType' => false,
            'validateOnChange' => false,
            'type' => ActiveForm::TYPE_VERTICAL,
        ]),
        'columns' => 1,
        'attributes' => [
            'email' => ['type' => Form::INPUT_TEXT],
            FormButtonsWidget::embed([
                'orientation' => FormButtonsWidget::ORIENTATION_BLOCK,
                'buttons' => [
                    Html::a(Yii::t('app', 'Already have an account? Sign in!'), ['/session/create']),
                    Html::submitButton(\Yii::t('app', 'Register'), ['class' => 'btn btn-primary'])
                ]
            ])
        ]
    ]);
    ActiveForm::end();