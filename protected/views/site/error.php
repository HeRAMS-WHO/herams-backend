<?php

use prime\helpers\Icon;
use yii\helpers\Html;
/** @var \Throwable $exception */
assert($exception instanceof \Throwable);
echo Html::beginTag('div', [
    'class' => 'centered',

]);
echo Html::img("/img/HeRAMS.png", [
    'class' => 'logo',
]);
echo Icon::bug();

if (empty($exception->getMessage()) && $exception instanceof \yii\web\HttpException) {
    echo Html::tag('span', $exception->getName(), ['class' => 'error']);
} else {
    echo Html::tag('span', $exception->getMessage(), ['class' => 'error']);
}

switch (get_class($exception)) {
    case \yii\web\NotFoundHttpException::class:
        $message = \Yii::t('app', 'The page you are looking for doesn\'t exist');
        break;
    default:
        $message = \Yii::t('app', 'No extra information is available at this time');
}
echo Html::tag('span', $message, ['class' => 'help']);
echo Html::a(Icon::home().\Yii::t('app', 'Home'), ['/'], ['target' => '_top', 'class' => 'btn btn-primary btn-home']);

echo Html::endTag('div');
