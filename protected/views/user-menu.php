<?php

use yii\helpers\Html;

?>
<div class="user-menu">
    <?= Html::a(\prime\helpers\Icon::signOutAlt(), ['/user/security/logout'], ['data-method' => 'POST']); ?>
    <?php
    /** @var \prime\models\ar\User $user */
    $user = \Yii::$app->user->identity;
    echo Html::img($user->getGravatarUrl(), [
        'referrerpolicy' => 'no-referrer'
    ]);

    ?>

    <div>
        <?= Html::a("{$user->firstName} {$user->lastName}", ['/user/settings/profile'], [
            'class' => 'name'
        ]); ?>
        <div class="email"><?= $user->email ?></div>
    </div>
    <?= Html::a(\prime\helpers\Icon::wrench(), ['/site/admin']); ?>
</div>