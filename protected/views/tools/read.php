<?php

use \app\components\Html;
use prime\models\ar\Setting;

/**
 * @var $model \prime\models\ar\Tool
 */

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::icon(Setting::get('icons.request')),
            'url' => ['/tools/request-access', 'id' => $model->id],
            'options' => [
                'class' => 'icon',
                'title' => \Yii::t('app', 'Request'),
            ],
        ]
    ]
];
?>

<div class="col-xs-12 col-md-6">
    <?=\app\components\Html::img($model->imageUrl, ['style' => ['width' => '100%']])?>
</div>
<div class="col-xs-12 col-md-6">
    <h1><?=$model->title?><?=app()->user->identity->isAdmin ? Html::a(Html::icon(Setting::get('icons.update')), ['tools/update', 'id' => $model->id]) : ''?></h1>
    <?=$model->description?>
</div>

