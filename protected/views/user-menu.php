<?php

use prime\helpers\Icon;
use yii\helpers\Html;

?>
<div class="user-menu">
    <?= Html::a(Icon::signOutAlt(), ['/user/security/logout'], ['data-method' => 'POST']); ?>
    <?php
    /** @var \prime\models\ar\User $user */
    $user = \Yii::$app->user->identity;
    ?>

    <div>
        <?= Html::a("{$user->firstName} {$user->lastName}", ['/user/settings/profile'], [
            'class' => 'name'
        ]); ?>
        <div class="email"><?= $user->email ?></div>
    </div>
    <?php
        echo Html::a(Icon::admin(), ['/admin/dashboard']);
    ?>
</div>