<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Menu;
use kartik\helpers\Html;

/**
 * @var \yii\web\View $this
 */
NavBar::begin([
    'brandLabel' => \kartik\helpers\Html::img('@web/img/logo.svg'),
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
            'label' => Html::icon('list', ['title' => 'Projects']),
            'url' => ['/projects'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('list-alt', ['title' => 'Reports']),
            'url' => ['/reports'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('globe', ['title' => 'Global monitor']),
            'url' => ['/marketplace'],
            'visible' => !Yii::$app->user->isGuest

        ],
        [
            'label' => Html::icon('heart-empty', ['title' => \Yii::t('app', 'User lists')]),
            'url' => ['/user-lists'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [

            'label' => Html::icon('search', ['title' => \Yii::t('app', 'Search')]),
            'url' => ['/search'],
            'visible' => false && !Yii::$app->user->isGuest // @todo Implement this.
        ],
        [
            'label' => Html::icon('user', ['title' => 'User']),
            'url' => ['/user/settings/account'],
            'visible' => !Yii::$app->user->isGuest

        ],
        [
            'label' => Html::icon('wrench', ['title' => Yii::t('app', 'Configuration')]),
            'items' => [
                ['label' => 'Tools', 'url' => ['/tools']],
                ['label' => 'Users & Permissions', 'url' => ['/rbac'], 'visible' => app()->user->can('admin')],
                ['label' => 'Site configuration', 'url' => ['/settings/index'], 'visible' => app()->user->can('admin')],

            ],
            'visible' => app()->user->can('tools')

        ],
        [
            'label' => Html::icon('log-in', ['title' => 'Log in']),
            'url' => ['/user/security/login'],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('log-out', ['title' => 'Log out']),
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