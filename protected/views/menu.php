<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Menu;
use kartik\helpers\Html;

/**
 * @var \yii\web\View $this
 */
//NavBar::begin([
//    'brandLabel' => \yii\helpers\ArrayHelper::getValue($this->params, 'brandLabel', \kartik\helpers\Html::img('@web/img/logo.png')),
//    'brandUrl' => Yii::$app->homeUrl,
//    'renderInnerContainer' => true,
//    'options' => [
//        'class' => 'navbar-default navbar-fixed-top',
//    ],
//]);
//
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => '',
            'items' => [
//                [
//                    'label' => Html::icon(\prime\models\ar\Setting::get('icons.globalMonitor', 'asterisk'), ['title' => 'Global monitor']),
//                    'url' => ['/marketplace'],
//                    'visible' => !Yii::$app->user->isGuest
//
//                ],
//                [
//                    'label' => Html::icon(\prime\models\ar\Setting::get('icons.projects', 'asterisk'), ['id' => 'projects', 'title' => 'Projects']),
//                    'url' => ['/projects'],
//                    'visible' => !Yii::$app->user->isGuest
//                ],
//                [
//                    'label' => Html::icon(\prime\models\ar\Setting::get('icons.userLists', 'asterisk'), ['title' => \Yii::t('app', 'User lists')]),
//                    'url' => ['/user-lists'],
//                    'visible' => !Yii::$app->user->isGuest
//                ],
//                [
//
//                    'label' => Html::icon(\prime\models\ar\Setting::get('icons.search', 'asterisk'), ['title' => \Yii::t('app', 'Search')]),
//                    'url' => ['/search'],
//                    'visible' => false && !Yii::$app->user->isGuest // @todo Implement this.
//                ],
                [
                    'label' =>
//                        Html::icon(\prime\models\ar\Setting::get('icons.user', 'asterisk'), ['title' => 'Profile']) .
                        \Yii::t('app', 'Profile'),
                    'url' => ['/user/settings/profile'],
                    'visible' => !Yii::$app->user->isGuest

                ],
//                [
//                    'label' => Html::icon(\prime\models\ar\Setting::get('icons.configuration', 'asterisk'), ['title' => Yii::t('app', 'Configuration')]),
//                    'items' => [
//                        ['label' => 'Tools', 'url' => ['/tools']],
//                        ['label' => 'Users & Permissions', 'url' => ['/rbac'], 'visible' => app()->user->can('admin')],
//                        ['label' => 'Site configuration', 'url' => ['/settings/index'], 'visible' => app()->user->can('admin')],
//
//                    ],
//                    'visible' => app()->user->can('tools')
//
//                ],
                [
                    'label' => Html::icon(\prime\models\ar\Setting::get('icons.logIn', 'asterisk'), ['title' => 'Login or sign up']),
                    'url' => ['/user/security/login'],
                    'visible' => Yii::$app->user->isGuest
                ],
                [
                    'label' =>
//                        Html::icon(\prime\models\ar\Setting::get('icons.logOut', 'asterisk'), ['title' => 'Log out']) .
                        \Yii::t('app', 'Log out'),
                    'url' => ['/user/security/logout'],
                    'linkOptions' => [
                        'data-method' => 'post'
                    ],
                    'visible' => !Yii::$app->user->isGuest
                ]
            ],
        ]
    ]
]);
//NavBar::end();
/**
 * Register tooltips.
 */
$this->registerJs('$(\'nav [title]\').tooltip({
    placement: "bottom",
    trigger: "hover",

 });');