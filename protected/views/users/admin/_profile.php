<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use kartik\widgets\ActiveForm;
use app\components\Form;
use yii\helpers\Html;

/**
 * @var yii\web\View 					$this
 * @var dektrium\user\models\User 		$user
 * @var dektrium\user\models\Profile 	$profile
 */

?>

<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>

<?php $form = \app\components\ActiveForm::begin([
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'formConfig' => [
        'labelSpan' => 3
//        'horizontalCssClasses' => [
//            'wrapper' => 'col-sm-9',
//        ],
    ],
]);

echo Form::widget([
    'form' => $form,
    'model' => $profile,
    'columns' => 1,
    "attributes" => [
        'first_name' => [
            'type' => Form::INPUT_TEXT,
        ],
        'last_name' => [
            'type' => Form::INPUT_TEXT
        ],
        'organization' => [
            'type' => Form::INPUT_TEXT,
        ],
        'country' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\select2\Select2::class,
            'options' => [
                'data' => $profile->countryOptions(),
                'options' => [
                    'placeholder' => \Yii::t('app', 'Country')
                ]
            ]
        ],
        'office' => [
            'type' => Form::INPUT_TEXT,
        ],
        'position' => [
            'type' => Form::INPUT_TEXT
        ],
        'phone' => [
            'type' => Form::INPUT_TEXT
        ],
        'phone_alternative' => [
            'type' => Form::INPUT_TEXT
        ],
        'other_contact' => [
            'type' => Form::INPUT_TEXT
        ]
    ],
    'options' => [

    ]
]);

?>




<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
