<?php

use function iter\reduce;
use prime\models\ar\Project;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

    <header><?=\Yii::t('app', 'Log in to HeRAMS') ?></header>
    <?php
    $this->title = \Yii::$app->name;
    $this->params['breadcrumbs'] = [];
    /** @var \yii\web\View $this */



    /** @var \dektrium\user\models\LoginForm $model */

    $form = ActiveForm::begin([
        'id' => 'login-form',


    ]);
    echo $form->field($model, 'login')->textInput([
        'placeholder' => 'Email',
    ]);
    echo $form->field($model, 'password')->passwordInput([
        'placeholder' => $model->getAttributeLabel('password')
    ]);

    ActiveForm::end();
    echo Html::beginTag('div', ['class' => 'actions']);
    echo Html::a(\Yii::t('app', "Reset password"), ['/user/forgot']);
    echo Html::a(\Yii::t('app', "Sign up"), ['/user/register']);
    echo Html::a(\Yii::t('app', "Resend confirmation"), ['/user/resend']);
    echo Html::submitButton('Log in', ['class' => 'button', 'form' => 'login-form']);
    echo Html::endTag('div');

    $projects = Project::find()->all();

    ?>
