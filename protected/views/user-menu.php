<?php

use prime\helpers\Icon;
use yii\helpers\Html;


/** @var \prime\models\ar\User $user */
$user = \Yii::$app->user->identity;
?>

<div class="user-menu">
  <div class="username">
  <?= Html::a($user->name, ['/user/account'], [
            'class' => 'name'
        ]); ?>
    <div class="email"><?= $user->email ?></div>
  </div>

  <?= Html::a(Icon::admin(), ['/admin/dashboard']); ?>
  <?= Html::a(Icon::signOutAlt(), ['/session/delete'], ['data-method' => 'delete']); ?>

</div>