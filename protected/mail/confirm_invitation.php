<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\mail\MessageInterface;
use yii\web\View;

/**
 * @var View $this
 * @var array $confirmationRoute
 * @var MessageInterface $message
 */

if (empty($message->getSubject())) {
    $message->setSubject('HeRAMS email verification');
}

?>
<h1>Welcome to HeRAMS!</h1>

<p>
    You are creating an account at herams.org.<br>
    To continue with the registration click <?= Html::a('here', Url::to($confirmationRoute, true)) ?>.<br>
</p>

<div class="cta">
    <?= Html::a('Continue registration', Url::to($confirmationRoute, true)); ?>
</div>

<p>
    If the links don't work please copy this into your browser:
</p>

<p>
    <?= Url::to($confirmationRoute, true); ?>
</p>

Thanks!<br>
<br>
- The HeRAMS Team
