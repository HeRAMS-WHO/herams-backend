<?php

use yii\bootstrap\Nav;

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
    'options' => [
        'class' => 'navbar-nav navbar-right',
        'style' => [
            'margin-right' => '0'
        ]
    ],
    'encodeLabels' => false,
    'items' => [

        [
            'label' => 'Admin',
            'visible' => \Yii::$app->user->can('admin'),
            'items' => [
                ['label' => 'Users & Permissions', 'url' => ['/rbac']],
                ['label' => 'Site configuration', 'url' => ['/settings/index']],
                [
                    'label' => 'Tools', 'url' => ['/tools'],
                    'visible' => app()->user->can('tools')

                ],
            ]
        ],
        [
            'label' => '',
            'items' => [

                [
                    'label' =>
                        \Yii::t('app', 'Profile'),
                    'url' => ['/user/settings/profile'],
                    'visible' => !Yii::$app->user->isGuest

                ],
                [
                    'label' =>
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