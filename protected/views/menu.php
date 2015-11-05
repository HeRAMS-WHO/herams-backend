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
    'options' => [
        'class' => 'navbar-default navbar-fixed-top',
    ],
]);

if (!app()->user->isGuest) {
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Tools', 'url' => ['/tools']],
            ['label' => 'Projects', 'url' => ['/projects']],
            ['label' => 'Marketplace', 'url' => ['/users/login']],
            ['label' => 'User lists', 'url' => ['/user-lists/list']],
            ['label' => 'Cou', 'url' => ['/countries/list'], 'visible' => app()->user->identity->isAdmin]
        ],
    ]);
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => Html::icon('search'),
            'options' => [
                'class' => 'icon',
                'title' => 'Search'
            ],
            'url' => ['users/account'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('user'),
            'options' => [
                'class' => 'icon',
                'title' => 'User'
            ],

            'url' => ['user/settings/account'],
            'visible' => !Yii::$app->user->isGuest

        ],
        [
            'label' => Html::icon('log-in'),
            'options' => [
                'class' => 'icon',
                'title' => 'Log in'
            ],

            'url' => ['/user/security/login'],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('log-out'),
            'options' => [
                'class' => 'icon',
                'title' => 'Log out'
            ],

            'url' => ['/user/security/logout'],
            'linkOptions' => ['data-method' => 'post'],
            'visible' => !Yii::$app->user->isGuest
        ]
    ],
]);
NavBar::end();

echo $this->render('subMenu');