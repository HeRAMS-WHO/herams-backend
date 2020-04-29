<?php

use prime\helpers\Icon;
use yii\helpers\Html;

?>
<div class="user-menu">
    <?php
    /** @var \prime\models\ar\User $user */
    $user = \Yii::$app->user->identity;

    echo Html::a(Icon::heart(), ['/user/favorites']);
    ?>

    <div>
        <?= Html::a($user->name, ['/user/account'], [
            'class' => 'name'
        ]); ?>
        <div class="email"><?= $user->email ?></div>
    </div>
    <?php
        echo Html::a(Icon::admin(), ['/admin/dashboard']);
        echo Html::a(Icon::signOutAlt(), ['/session/delete'], ['data-method' => 'delete']);
    ?>
</div>