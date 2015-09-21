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
        'renderInnerContainer' => false
    ]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => [
        ['label' => 'Tools', 'url' => ['users/login']],
        ['label' => 'Projects', 'url' => ['users/login']],
        ['label' => 'Marketplace', 'url' => ['users/login']]
    ],
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => Html::icon('search'),
            'url' => ['users/account'],
            'visible' => Yii::$app->user->isGuest

        ],

        [
            'label' => Html::icon('user'),
            'url' => ['users/account'],
            'visible' => Yii::$app->user->isGuest

        ],


        Yii::$app->user->isGuest ?
            [
                'label' => Html::icon('log-in'),
                'url' => ['users/login']
            ] :
            ['label' => Html::icon('log-out'),
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']],
    ],
]);
NavBar::end();