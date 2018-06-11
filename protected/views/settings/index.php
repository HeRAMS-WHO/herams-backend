<?php
/** @var \yii\web\View $this */

use yii\helpers\Html;

/** @var \prime\models\forms\Settings $settings */

$this->title = \Yii::t('app', 'Update application settings');

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Back home'),
    'url' => '/'
];

$form = \kartik\form\ActiveForm::begin([
    'id' => 'settings',
    'method' => 'post',
    'type' => \kartik\form\ActiveForm::TYPE_HORIZONTAL
]);

foreach ($settings->safeAttributes() as $setting) {
    if(substr($setting, 0, 6) == 'icons.') {
        echo $form->field($settings, $setting)->dropDownList($settings->iconOptions(), ['class' => ['glyphicon']]);
    } else {
        $options = lcfirst($setting) . 'Options';
        if(method_exists($settings, $options)) {
            echo $form->field($settings, $setting)->dropDownList($settings->$options());
        } else {
            echo $form->field($settings, $setting)->textInput();
        }
    }
}

echo \yii\bootstrap\ButtonGroup::widget([
    'options' => [
        'class' => 'pull-right'
    ],
    'buttons' => [
        Html::submitButton(\Yii::t('app', 'Save'), ['form' => 'settings', 'class' => 'btn btn-primary'])
    ]
]);
$form->end();
