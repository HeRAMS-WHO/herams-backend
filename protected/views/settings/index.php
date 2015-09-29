<?php
/** @var \yii\web\View $this */
/** @var \prime\models\forms\user\Settings $settings */

$form = \kartik\form\ActiveForm::begin([
    'id' => 'settings',
    'type' => \kartik\form\ActiveForm::TYPE_HORIZONTAL
]);

foreach ($settings->safeAttributes() as $setting) {
    echo $form->field($settings, $setting)->textInput();
}
$form->end();
