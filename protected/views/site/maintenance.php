<?php

declare(strict_types=1);

use prime\helpers\Icon;
use yii\helpers\Html;

echo Html::beginTag('div', [
    'class' => 'centered',

]);
echo Html::img("/img/HeRAMS.svg", [
    'class' => 'logo',
]);
echo Icon::bug();

echo Html::tag('span', \Yii::t('app', 'Down for maintenance'), ['class' => 'help']);
echo Html::endTag('div');
