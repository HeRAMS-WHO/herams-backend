<?php

use kartik\widgets\ActiveForm;
use app\components\Form;
use yii\bootstrap\Html;

$this->title = Yii::t('user', 'Account settings');

?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('@dektrium/user/views/settings/_menu') ?>
    </div>
    <div class="col-md-9">
        <?php
        $form = ActiveForm::begin([
            'id' => 'settings-account-form',
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
                'new_password' => [
                    'type' => Form::INPUT_PASSWORD,
                ],
                'confirm_new_password' => [
                    'type' => Form::INPUT_PASSWORD,
                ],
                'email' => [
                    'type' => Form::INPUT_HTML5,
                    'html5type' => 'email'
                ],
                'current_password' => [
                    'type' => Form::INPUT_PASSWORD,
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