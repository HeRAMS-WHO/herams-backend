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
        ],
        [
            'label' => \Yii::t('app', 'edit'),
            'url' => ['tools/update', 'id' => $model->id],
            'visible' => app()->user->identity->isAdmin,
        ]
    ]
];
?>

<div class="col-xs-12 col-md-6">
    <?=\app\components\Html::img($model->imageUrl, ['style' => ['width' => '100%']])?>
</div>
<div class="col-xs-12 col-md-6">
    <h2><?=$model->title?></h2>
    <?=$model->description?>
</div>

