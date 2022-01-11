<?php
/** @var \prime\models\ar\User $user */

/** @var array $verificationRoute */

use yii\helpers\Url; ?>
<h1>Welcome to HeRAMS!</h1>

<p>
    Someone requested an account for you at herams.org.<br>
    To continue with registration click <?=\yii\helpers\Html::a('here', Url::to($verificationRoute, true)) ?>.
</p>

<div class="cta">
    <?=\yii\helpers\Html::a('Create account', Url::to($verificationRoute, true)); ?>
</div>

If the links don't work please copy this into your browser:<br><br>

<?= Url::to($verificationRoute, true); ?>

Thanks!<br>
<br>
- The HeRAMS Team