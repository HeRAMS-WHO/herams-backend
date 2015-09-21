<?php
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Menu;
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
        'items' => [
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['users/login']] :
                ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']],
        ],
    ]);
    NavBar::end();