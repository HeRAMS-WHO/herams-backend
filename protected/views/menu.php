<?php
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Menu;
    use kartik\helpers\Html;
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
            ['label' => 'Tools', 'url' => ['users/login']],
            ['label' => 'Projects', 'url' => ['users/login']],
            ['label' => 'Marketplace', 'url' => ['users/login']]
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

            'url' => ['user/security/login'],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            'label' => Html::icon('log-out'),
            'options' => [
                'class' => 'icon',
                'title' => 'Log out'
            ],

            'url' => ['user/security/logout'],
            'linkOptions' => ['data-method' => 'post'],
            'visible' => !Yii::$app->user->isGuest
        ]
    ],
]);
NavBar::end();