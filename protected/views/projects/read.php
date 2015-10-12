<?php

use app\components\Html;

/**
 * @var \prime\models\Project $model
 * @var \yii\web\View $this
 */

$this->params['subMenu']['items'] = [];

if($model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)) {
    $this->params['subMenu']['items'][] = [
        'label' => \Yii::t('app', 'share'),
        'url' => ['projects/share', 'id' => $model->id]
    ];
}

if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
    $this->params['subMenu']['items'][] = [
        'label' => \Yii::t('app', 'update'),
        'url' => ['projects/update', 'id' => $model->id]
    ];
}

?>

<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-10">
            <h2><?=$model->title?></h2>
        </div>
        <div class="col-xs-2">
            <?=Html::img($model->tool->imageUrl, ['style' => ['width' => '100%']])?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-9">
            Type of crisis:<br>
            End Date:
        </div>
        <div class="col-xs-12 col-md-3">
            Profile ofzo...
        </div>
    </div>
</div>

<div class="col-xs-12 col-md-9">

</div>

<div class="col-xs-12 col-md-3">
    test 3
</div>

