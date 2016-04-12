<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Menu;
use kartik\helpers\Html;

/**
 * @var \yii\web\View $this
 */
NavBar::begin([
    'brandLabel' => \yii\helpers\ArrayHelper::getValue($this->params, 'brandLabel', \kartik\helpers\Html::img('@web/img/logo.svg')),
    'brandUrl' => Yii::$app->homeUrl,
    'renderInnerContainer' => true,
    'options' => [
        'class' => 'navbar-default navbar-fixed-top',
    ],
]);

if (!app()->user->isGuest) {
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'encodeLabels' => false,
        'items' => [


        ],
    ]);
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => Html::icon(\prime\models\ar\Setting::get('icons.globalMonitor'), ['title' => 'Global monitor']),
            'url' => ['/marketplace'],
            'visible' => !Yii::$app->user->isGuest

        ],
        [
            'label' => Html::icon('tint', ['title' => 'Shiny']),
            'url' => ['/projects/explore'],
            'visible' => !Yii::$app->user->isGuest

        ],
        [
            'label' => Html::icon(\prime\models\ar\Setting::get('icons.projects'), ['id' => 'projects', 'title' => 'Projects']),
            'url' => ['/projects'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon(\prime\models\ar\Setting::get('icons.reports'), ['title' => 'Reports']),
            'url' => ['/reports'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon(\prime\models\ar\Setting::get('icons.userLists'), ['title' => \Yii::t('app', 'User lists')]),
            'url' => ['/user-lists'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [

            'label' => Html::icon(\prime\models\ar\Setting::get('icons.search'), ['title' => \Yii::t('app', 'Search')]),
            'url' => ['/search'],
            'visible' => false && !Yii::$app->user->isGuest // @todo Implement this.
        ],
        [
            'label' => Html::icon(\prime\models\ar\Setting::get('icons.user'), ['title' => 'User']),
            'url' => ['/user/settings/profile'],
            'visible' => !Yii::$app->user->isGuest

        ],
        [
            'label' => Html::icon(\prime\models\ar\Setting::get('icons.configuration'), ['title' => Yii::t('app', 'Configuration')]),
            'items' => [
                ['label' => 'Tools', 'url' => ['/tools']],
                ['label' => 'Users & Permissions', 'url' => ['/rbac'], 'visible' => app()->user->can('admin')],
                ['label' => 'Site configuration', 'url' => ['/settings/index'], 'visible' => app()->user->can('admin')],

            ],
            'visible' => app()->user->can('tools')

        ],
        [
            'label' => Html::icon(\prime\models\ar\Setting::get('icons.logIn'), ['title' => 'Login or sign up']),
            'url' => ['/user/security/login'],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon(\prime\models\ar\Setting::get('icons.logOut'), ['title' => 'Log out']),
            'url' => ['/user/security/logout'],
            'linkOptions' => [
                'data-method' => 'post'
            ],
            'visible' => !Yii::$app->user->isGuest
        ]
    ],
]);
NavBar::end();
/**
 * Register tooltips.
 */
$this->registerJs('$(\'nav [title]\').tooltip({
    placement: "bottom",
    trigger: "hover",

 });');
echo $this->render('subMenu');