<?php

use prime\helpers\Icon;
use yii\helpers\Html;
use prime\models\ar\Permission;
use prime\widgets\menu\TabMenu;

$this->title = \Yii::t('app', 'Backend administration');
$this->params['breadcrumbs'][] = ['label' => ""];




$tabs = [
    [
        'url' => ['admin/dashboard'],
        'title' => \Yii::t('app', 'Dashboard')
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN)) {
    $tabs[] =
        [
            'url' => ['user/index'],
            'title' => \Yii::t('app', 'Users')
        ];
    $tabs[] =
        [
            'url' => ['admin/share'],
            'title' => \Yii::t('app', 'Global permissions')
        ];
    $tabs[] =
        [
            'url' => ['admin/limesurvey'],
            'title' => \Yii::t('app', 'Backend administration')
        ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->uniqueId
]);

echo Html::beginTag('div', ['class' => 'content']);

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
