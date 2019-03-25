<?php

use prime\helpers\Icon;

/** @var \yii\web\View $this */
echo \yii\helpers\Html::tag('iframe', '', [
    'src' => \yii\helpers\Url::to(['/site/lime-survey']),
    'style' => [
        'position' => 'absolute',
        'left' => 0,
        'top' => 0,
        'width' => '100%',
        'height' => '100%'
        //'height' => '800px'
    ]
]);

echo Icon::windowMaximize([
    'id' => 'maximize',
    'style' => [
        'position' => 'absolute',
        'z-index' => 1000,
        'top' => '5px',
        'right' => '20px'
    ]

]);

$this->registerJs(<<<JS
    document.getElementById('maximize').addEventListener('click', function() {
       document.querySelector('div.content').classList.toggle('maximized'); 
        
    });


JS
);