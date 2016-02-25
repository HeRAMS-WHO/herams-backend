<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\User $user
 */

?>

<div class="row">
    <div class="col-xs-3">
        <?=Html::img($user->gravatarUrl, ['style' => ['width' => '100%']])?>
    </div>
    <div class="col-xs-9">
        <div class="row">
            <div class="col-xs-12">
                <?=$user->name?><br>
                <?=$user->profile->organization?><br>
                <?=Html::a(\Yii::t('app', 'Email'), 'mailto:' . $user->email)?>
            </div>
        </div>
    </div>
</div>