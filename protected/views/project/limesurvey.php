<?php

declare(strict_types=1);

use prime\helpers\Icon;
use prime\widgets\menu\ProjectTabMenu;
use yii\helpers\Html;

/**
 * @var \prime\components\View $this
 * @var \prime\models\ar\Project $project
 */
$this->title = $project->title;
$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();

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

$this->registerCss(<<<CSS
    #maximize {
        pointer-events: initial;
        position: absolute;
        z-index: 1000;
        top: 5px;
        font-size: 50px;
        color: #333333;
        right: 20px;
        opacity: 0;
        
    }

    iframe {
        opacity: 0;
    }
    iframe.fade-in {
        opacity: 1;
        transition: all 3s;
    }
    
    
    #maximize.focused {
        opacity: 1;
        font-size: 15px;
        transition: all 1s ease-in-out;
    }

    #maximize:hover {
        font-size: 50px;
        transition: none;
    }
CSS);

$this->registerJs(<<<JS
    (() => {
        let frame = document.querySelector('iframe');
        frame.addEventListener('load', function() {
            frame.classList.add('fade-in');
            elem.classList.add('focused');
        });
        let elem = document.getElementById('maximize');
        elem.addEventListener('click', function() {
           document.querySelector('div.content').classList.toggle('maximized'); 
            
        });
    })();
    


JS);

echo Html::endTag('div');
