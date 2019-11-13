<?php

use collecthor\widgets\Panel\Panel;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model collecthor\models\User */

$this->title = Yii::t('app', 'Update account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo Html::beginTag('div', [
    'class' => 'col-md-6'
]);
echo \yii\bootstrap\Tabs::widget([
    'items' => [
        [
            'label' => 'Profile',
            'content' => $this->render('_accountForm', ['model' => $model])
        ],
        [
            'label' => 'Password',
            'content' => $this->render('update-password', ['model' => $changePassword])
        ],
        [
            'label' => 'Email',
            'content' => $this->render('update-email', ['model' => $changeMail])
        ]
    ]
]);
echo Html::endTag('div');