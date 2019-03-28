<?php

use prime\helpers\Icon;
use yii\helpers\Html;

echo Html::beginTag('div', [
    'class' => 'centered',

]);
echo Html::img("/img/HeRAMS.png", [
    'class' => 'logo',
]);
echo Icon::bug();
//var_dump($exception); die();
echo Html::tag('span', $exception->getMessage(), ['class' => 'error']);
switch(get_class( $exception)) {
    case \yii\web\NotFoundHttpException::class:
        $message = \Yii::t('app', 'The page you are looking for doesn\'t exist');
        break;
    default:
        $message = \Yii::t('app', 'No extra information is available at this time');
}
echo Html::tag('span', $message, ['class' => 'help']);

echo Html::endTag('div');

$this->registerCss(<<<CSS
    div.centered {
        grid-column: span 2;
        text-align: center;
        padding-left: 150px;
        padding-right: 150px;
        padding-bottom: 40px;
        font-size: 40px;
        color: #bbbbbb;
    }
    
    div.centered span.help {
        margin-top: 30px;
        font-size: 20px;
    }
    
    div.centered > svg {
        font-size: 46px;
        margin-top: 35px;
    }
    div.centered .logo {
        max-width: 268px;
    }
    div.centered > * {
        display: block;
        margin: auto;
        margin-top: 10px;
    }

CSS
);