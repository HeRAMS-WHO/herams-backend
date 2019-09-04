<?php

use app\components\Form;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('user', 'Profile settings');

/** @var \prime\models\ar\Profile $model */
?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('@dektrium/user/views/settings/_menu') ?>
    </div>
    <div class="col-md-9">
        <?php
        $form = ActiveForm::begin([
            'id' => 'settings-profile-form',
            'method' => 'POST',
            "type" => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'showLabels' => true,
                'defaultPlaceholder' => true
            ]
        ]);

        echo Form::widget([
            'form' => $form,
            'model' => $model,
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
                ],
                'actions' => [
                    'type' => Form::INPUT_RAW,
                    'value' =>
                        Html::submitButton(\Yii::t('app', 'Submit'), ['class' => 'btn btn-primary col-xs-12'])
                ]
            ],
            'options' => [

            ]
        ]);

        $form->end();
        ?>
    </div>
</div>