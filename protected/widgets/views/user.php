<?php

use app\components\Html;

/**
 * @var \prime\widgets\User $widget
 */

?>

<div class="row">
    <div class="col-xs-3">
        <?=Html::img($widget->user->gravatarUrl, ['style' => ['width' => '100%']])?>
    </div>
    <div class="col-xs-9">
        <div class="row">
            <div class="col-xs-12">
                <?=$widget->user->name?>
            </div>
            <div class="col-xs-12">
                <?=Html::a(\Yii::t('app', 'Email'), 'mailto:' . $widget->user->email)?>
                <?=Html::a(\Yii::t('app', 'Profile'), ['users/read', 'id' => $widget->user->id])?>
            </div>
        </div>
    </div>
</div>