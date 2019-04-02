<?php

use prime\models\ar\Project;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

<header><?=\Yii::t('app', 'Register for HeRAMS') ?></header>
<?php
$this->title = \Yii::$app->name;
$this->params['breadcrumbs'] = [];
/** @var \yii\web\View $this */



/** @var \prime\models\forms\user\Registration $model */

$form = ActiveForm::begin([
    'id' => 'signup-form',


]);

echo $form->errorSummary($model);
echo $form->field($model, 'first_name')->textInput([
    'placeholder' => $model->getAttributeLabel('first_name')
]);
echo $form->field($model, 'last_name')->textInput([
    'placeholder' => $model->getAttributeLabel('last_name')
]);
echo $form->field($model, 'email')->textInput([
    'placeholder' => $model->getAttributeLabel('email')
]);
echo $form->field($model, 'password')->passwordInput([
    'placeholder' => $model->getAttributeLabel('password')
]);

echo $form->field($model, 'confirmPassword')->passwordInput([
    'placeholder' => $model->getAttributeLabel('confirmPassword')
]);

ActiveForm::end();
echo Html::beginTag('div', ['class' => 'actions']);
echo Html::submitButton('Sign up', ['class' => 'button', 'form' => 'signup-form']);
echo Html::endTag('div');

$projects = Project::find()->all();

?>
