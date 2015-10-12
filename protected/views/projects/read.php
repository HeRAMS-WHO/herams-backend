<?php

/**
 * @var $model \prime\models\Project
 */

vd($model);

if($model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)) {
    echo \app\components\Html::a(\Yii::t('app', 'Share'), ['projects/share', 'id' => $model->id], ['class' => 'btn btn-primary']);
}