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
            'label' => Html::icon('list'),
            'linkOptions' => [
                'title' => 'Projects'
            ],
            'url' => ['/projects']
        ],

        [
            'label' => Html::icon('globe'),
            'linkOptions' => [
                'title' => 'Marketplace'
            ],
            'url' => ['/marketplace/map']

        ],
        [
            'label' => Html::icon('heart-empty'),
            'linkOptions' => [
                'title' => \Yii::t('app', 'User lists')
            ],
            'url' => ['/user-lists/list']
        ],
        [

            'label' => Html::icon('search') . " " . Html::tag('span', \Yii::t('app', 'Search'), ['class' => 'visible-xs-inline-block']),
            'linkOptions' => [
                'title' => \Yii::t('app', 'Search')
            ],
            'url' => ['users/account'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('user'),
            'linkOptions' => [
                'title' => 'User'
            ],

            'url' => ['user/settings/account'],
            'visible' => !Yii::$app->user->isGuest

        ],
        [
            'label' => Html::icon('wrench'),
            'items' => [
                ['label' => 'Tools', 'url' => ['/tools']],
                ['label' => 'Users & Permissions', 'url' => ['/rbac'], 'visible' => app()->user->can('admin')],
                ['label' => 'Countries', 'url' => ['/countries/list'], 'visible' => app()->user->can('admin')],
                ['label' => 'Site configuration', 'url' => ['/settings/index'], 'visible' => app()->user->can('admin')],

            ],

//            'url' => ['user/settings/account'],
            'visible' => app()->user->can('tools')

        ],
        [
            'label' => Html::icon('log-in'),
            'linkOptions' => [
                'title' => 'Log in'
            ],

            'url' => ['/user/security/login'],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('log-out'),
            'linkOptions' => [
                'title' => 'Log out'
            ],

            'url' => ['/user/security/logout'],
            'linkOptions' => [
                'title' => Yii::t('app', 'Log out'),
                'data-method' => 'post'
            ],
            'visible' => !Yii::$app->user->isGuest
        ]
    ],
]);
NavBar::end();

echo $this->render('subMenu');