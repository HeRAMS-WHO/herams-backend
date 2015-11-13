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
            'label' => Html::icon('list', [
                'title' => 'Projects'
            ]),
            'url' => ['/projects']
        ],

        [
            'label' => Html::icon('globe', [
                'title' => 'Marketplace'
            ]),
            'url' => ['/marketplace/map']

        ],
        [
            'label' => Html::icon('heart-empty', [
                'title' => \Yii::t('app', 'User lists')
            ]),
            'url' => ['/user-lists/list']
        ],
        [

            'label' => Html::icon('search', [
                    'title' => \Yii::t('app', 'Search')
            ]),
            'url' => ['users/account'],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('user', [
                'title' => 'User'
            ]),

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

            'url' => ['user/settings/account'],
            'visible' => app()->user->can('tools')

        ],
        [
            'label' => Html::icon('log-in', [
                'title' => 'Log in'
            ]),

            'url' => ['/user/security/login'],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('log-out', [
                'title' => 'Log out'
            ]),

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