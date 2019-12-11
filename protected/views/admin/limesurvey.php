<?php

use prime\helpers\Icon;

$this->title= \Yii::t('app', 'Backend administration');
$this->params['breadcrumbs'][] = [
    'label' => $this->title
];

/** @var \yii\web\View $this */
echo \yii\helpers\Html::tag('iframe', '', [
    'src' => \yii\helpers\Url::to(['/site/lime-survey']),
    'style' => [
        'position' => 'absolute',
        'left' => 0,
        'top' => 0,
        'width' => '100%',
        'height' => '100%'
    ]
]);

echo Icon::windowMaximize([
    'id' => 'maximize',
]);


$this->registerJs(<<<JS
    let frame = document.querySelector('iframe');
    frame.addEventListener('load', function() {
        frame.classList.add('fade-in');
        elem.classList.add('focused');
    });
    let elem = document.getElementById('maximize');
    elem.addEventListener('click', function() {
       document.querySelector('div.content').classList.toggle('maximized'); 
        
    });
    


JS
);