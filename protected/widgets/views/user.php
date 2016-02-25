<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\User $user
 */

?>

<div class="row">
    <div class="col-xs-3" style="position: relative">
        <?=Html::img($user->gravatarUrl, ['style' => ['width' => '100%', 'z-index' => 5]])?>
        <?=Html::icon('user', ['style' => ['position' => 'absolute', 'font-size' => '60px', 'color' => '#DB3C26', 'width' => '100%', 'text-align' => 'center', 'z-index' => 4]])?>
    </div>
    <div class="col-xs-9">
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo implode('<br>', array_filter([
                        $user->name,
                        $user->profile->organization,
                        Html::a(\Yii::t('app', 'Email'), 'mailto:' . $user->email)
                    ]));
                ?>
            </div>
        </div>
    </div>
</div>