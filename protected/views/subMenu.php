<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Menu;
use kartik\helpers\Html;

/**
 * @var \yii\web\View $this
 */

if(isset($this->params['subMenu'], $this->params['subMenu']['items']) && !empty($this->params['subMenu']['items'])) {
    NavBar::begin(
        [
            'options' => [
                'class' => 'navbar-default',
            ],
        ]
    );

    echo Nav::widget(
        [
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => $this->params['subMenu']['items'],
        ]
    );
    NavBar::end();
}