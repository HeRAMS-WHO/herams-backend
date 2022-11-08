<?php

use herams\common\domain\user\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\mail\MessageInterface;
use yii\web\View;

/**
 * @var View $this
 * @var User $user
 * @var array $invitationRoute
 * @var int $linkExpirationDays
 * @var MessageInterface $message
 */

if (empty($message->getSubject())) {
    $message->setSubject('HeRAMS account invitation');
}

?>
<h1>Welcome to HeRAMS!</h1>

<p>
    <?= $user->name ?> requested an account for you at herams.org.<br>
    To continue with registration click <?= Html::a('here', Url::to($invitationRoute, true)) ?>.<br>
    The link will expire in <?= $linkExpirationDays ?> days, request a new link from <?= $user->name ?> when it does.
</p>

<div class="cta">
    <?= Html::a('Accept invitation', Url::to($invitationRoute, true)); ?>
</div>

<p>
    If the links don't work please copy this into your browser:
</p>

<p>
    <?= Url::to($invitationRoute, true); ?>
</p>

Thanks!<br>
<br>
- The HeRAMS Team
