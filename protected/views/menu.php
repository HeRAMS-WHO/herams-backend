<?php

use prime\models\ar\Project;
use yii\helpers\Html;

echo Html::beginTag('div', ['class' => 'menu']);
    echo Html::img("/img/HeRAMS.png");
    echo Html::beginTag('div',['class'=>'line']);
    echo Html::endTag('div');
    echo Html::beginTag('nav');
        $projects = [];
        echo Html::a('Projects', ['/project/index']);
        if (\Yii::$app->user->can('admin')) {
            echo Html::a('Users', ['/user/index']);
        }
        echo Html::a(\Yii::t('app', 'Backend administration'), ['/admin/limesurvey']);
    echo Html::endTag('nav');
    echo $this->render('//footer', ['projects' => Project::find()->all()]);

echo Html::endTag('div');
