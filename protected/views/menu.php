<?php

use prime\helpers\Icon;
use yii\helpers\Html;

echo Html::beginTag('div', ['class' => 'menu']);
    echo Html::img("/img/HeRAMS.png");
    echo Html::tag('hr');
    echo Html::beginTag('nav');
        $projects = [];
        echo Html::a('Projects', ['/project/index']);
        if (\Yii::$app->user->can('admin')) {
            echo Html::a('Users', ['/user/admin/index']);
        }
    echo Html::endTag('nav');

    echo Html::beginTag('div',[
        'class' => 'footer'
    ]);
        echo Icon::mapMarkedAlt(['class' => 'subject']);
        echo Html::tag('div', count($projects), [
            'class' => 'counter'
        ]);
        echo Html::tag('div', 'HeRAMS projects', [
            'class' => 'subject'
        ]);
        echo Html::beginTag('div', ['class' => 'status']);
            echo Icon::sync();
        echo Html::endTag('div');
    echo Html::endTag('div');
echo Html::endTag('div');