<?php

use app\components\Form;
use app\components\Html;
use app\components\ActiveForm;

$this->title = Yii::t('user', 'Profile settings');

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
                'showLabels' => false,
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
                'country' => [
                    'type' => Form::INPUT_TEXT,
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