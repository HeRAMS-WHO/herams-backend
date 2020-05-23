<?php

use prime\helpers\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="user-menu">
    <?php
    /** @var \prime\models\ar\User $user */
    $user = \Yii::$app->user->identity;

    ?>
    <?php
        echo Html::tag('span', \Yii::$app->language);
        echo Html::a(Icon::star(), ['/user/favorites']);
        echo Html::a(Icon::admin(), ['/admin/dashboard']);
        echo Html::a(Icon::question(), Url::to('https://docs.herams.org/'), ['target'=> '_blank']);
        echo Html::a(Icon::signOutAlt(), ['/session/delete'], ['data-method' => 'delete']);
    ?>

    <div>
        <?= Html::a($user->name, ['/user/account'], [
            'class' => 'name'
        ]); ?>
        <div class="email"><?= $user->email ?></div>
    </div>
</div>