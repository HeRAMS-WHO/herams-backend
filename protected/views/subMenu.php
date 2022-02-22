<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/**
 * @var \yii\web\View $this
 */
assert($this instanceof \prime\components\View);
$visible = false;

if (isset($this->params['subMenu'], $this->params['subMenu']['items'])) {
    foreach ($this->params['subMenu']['items'] as $item) {
        $visible = $visible || !isset($item['visible']) || $item['visible'];
    }
}


if ($visible) {
    NavBar::begin([
        'renderInnerContainer' => true,
        'options' => [
            'class' => 'navbar-default navbar-static-top',
            'style' => [
//                'margin-top' => '-20px',
//                    'top' => '70px',
                'z-index' => 10
            ]
        ],
    ]);

    echo Nav::widget(
        [
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => $this->params['subMenu']['items'],
        ]
    );
    NavBar::end();
}
