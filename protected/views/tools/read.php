<?php

use \app\components\Html;

/**
 * @var $model \prime\models\Tool
 */

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => \Yii::t('app', 'request for activation'),
            'url' => '#'
        ]
    ]
];
?>

<div class="col-xs-12 col-md-6">
    <?=\app\components\Html::img($model->imageUrl, ['style' => ['width' => '100%']])?>
</div>
<div class="col-xs-12 col-md-6">
    <h1><?=$model->title?><?=app()->user->identity->isAdmin ? Html::a(Html::icon('pencil'), ['tools/update', 'id' => $model->id]) : ''?></h1>
    <?=$model->description?>
</div>

